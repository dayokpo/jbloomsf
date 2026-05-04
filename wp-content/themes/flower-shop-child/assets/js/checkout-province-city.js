(function ($) {
    'use strict';

    var config = window.flowerShopCheckoutData || {};
    var provinceOptions = config.provinceOptions || {};
    var addressDataUrl = config.cityDataUrl || '';
    var barangayDataUrl = config.barangayDataUrl || '';
    var selectCityText = (config.i18n && config.i18n.selectCity) || 'Select City / Municipality';
    var selectBarangayText = (config.i18n && config.i18n.selectBarangay) || 'Select Barangay';

    var cityData = null;
    var barangayData = null;
    var refreshTimer = null;
    var reorderTimer = null;
    var pendingResetPrefixes = {};
    var latestSelectedCities = {};
    var latestSelectedPostcodes = {};
    var latestSelectedBarangays = {};
    var checkoutFetchSyncInstalled = false;

    function getAddressFieldValue(prefix, field) {
        var $generatedSelect = $('select.flower-shop-generated-' + field + '-select[data-prefix="' + prefix + '"]').first();

        if ($generatedSelect.length) {
            return String($generatedSelect.val() || '').trim();
        }

        var $field = findField(prefix, field === 'barangay' ? 'barangay' : field);
        return $field.length ? String($field.val() || '').trim() : '';
    }

    function ensureCheckoutAddressPayload(addressData, prefix, fallbackPrefix) {
        var next = addressData || {};
        var city = getAddressFieldValue(prefix, 'city');
        var barangay = getAddressFieldValue(prefix, 'barangay');

        if (!city && fallbackPrefix) {
            city = getAddressFieldValue(fallbackPrefix, 'city');
        }

        if (!barangay && fallbackPrefix) {
            barangay = getAddressFieldValue(fallbackPrefix, 'barangay');
        }

        if (city) {
            next.city = city;
        }

        if (barangay) {
            next.address_1 = barangay;
        }

        return next;
    }

    function installCheckoutPayloadSync() {
        if (!window.fetch || checkoutFetchSyncInstalled) {
            return;
        }

        checkoutFetchSyncInstalled = true;
        var originalFetch = window.fetch.bind(window);

        window.fetch = function (input, init) {
            var url = typeof input === 'string' ? input : ((input && input.url) || '');
            var isCheckoutRequest = url.indexOf('/wp-json/wc/store/v1/checkout') !== -1;
            var requestInit = init;

            if (isCheckoutRequest && requestInit && typeof requestInit.body === 'string') {
                try {
                    var parsed = JSON.parse(requestInit.body);

                    if (parsed && typeof parsed === 'object') {
                        parsed.billing_address = ensureCheckoutAddressPayload(parsed.billing_address, 'billing', 'shipping');
                        parsed.shipping_address = ensureCheckoutAddressPayload(parsed.shipping_address, 'shipping', 'billing');

                        requestInit = Object.assign({}, requestInit, {
                            body: JSON.stringify(parsed)
                        });
                    }
                } catch (e) {
                    requestInit = init;
                }
            }

            return originalFetch(input, requestInit);
        };
    }

    function findAddressFieldContainer($form, prefix, fieldKey) {
        var $inputByName = $form.find('[name="' + prefix + '_' + fieldKey + '"]').first();
        var $container = $inputByName.closest('[class*="wc-block-components-address-form__"]');

        if ($container.length) {
            return $container;
        }

        return $form.find('.wc-block-components-address-form__' + fieldKey).first();
    }

    function reorderBlockAddressFields(prefix) {
        var $form = $('[name="' + prefix + '_country"]').first().closest('.wc-block-components-address-form');

        if (!$form.length) {
            $form = $('[name="' + prefix + '_first_name"]').first().closest('.wc-block-components-address-form');
        }

        if (!$form.length) {
            return;
        }

        var orderedKeys = ['first_name', 'last_name', 'country', 'state', 'city', 'address_1', 'address_2'];
        var nodes = [];

        orderedKeys.forEach(function (fieldKey) {
            var $container = findAddressFieldContainer($form, prefix, fieldKey);

            if ($container.length && nodes.indexOf($container.get(0)) === -1) {
                nodes.push($container.get(0));
            }
        });

        if (!nodes.length) {
            return;
        }

        var $postcodeContainer = findAddressFieldContainer($form, prefix, 'postcode');

        nodes.forEach(function (node) {
            if ($postcodeContainer.length) {
                $postcodeContainer.before(node);
            } else {
                $form.append(node);
            }
        });
    }

    function reorderBlockAddressLayout() {
        reorderBlockAddressFields('billing');
        reorderBlockAddressFields('shipping');
    }

    function scheduleBlockAddressReorder() {
        if (reorderTimer) {
            window.clearTimeout(reorderTimer);
        }

        reorderTimer = window.setTimeout(function () {
            reorderBlockAddressLayout();
        }, 40);
    }

    function hasGeneratedSelect(prefix, type) {
        return $('select.flower-shop-generated-' + type + '-select[data-prefix="' + prefix + '"]').length > 0;
    }

    function ensureAddressControlsReady() {
        if (!cityData) {
            return;
        }

        ['billing', 'shipping'].forEach(function (prefix) {
            var $cityField = findField(prefix, 'city');
            var $barangayField = findField(prefix, 'barangay');
            var needsCityReinit = $cityField.length && $cityField.is('input[type="text"]') && !hasGeneratedSelect(prefix, 'city');
            var needsBarangayReinit = $barangayField.length && $barangayField.is('input[type="text"]') && !hasGeneratedSelect(prefix, 'barangay');

            if (needsCityReinit) {
                repopulateCitySelect(prefix, false);
            }

            if (needsBarangayReinit) {
                repopulateBarangaySelect(prefix, false);
            }
        });
    }

    function normalize(value) {
        return String(value || '')
            .toLowerCase()
            .replace(/\./g, '')
            .replace(/\s+/g, ' ')
            .trim();
    }

    function getProvinceAliases() {
        return {
            'compostela valley': 'davao de oro',
            'metro manila': 'metro manila'
        };
    }

    function resolveProvinceKey(code) {
        if (!code || !cityData) {
            return '';
        }

        var label = provinceOptions[code] || '';
        var normalizedLabel = normalize(label);
        var aliases = getProvinceAliases();
        var target = aliases[normalizedLabel] || normalizedLabel;
        var matchedKey = '';

        $.each(cityData, function (provinceName) {
            if (normalize(provinceName) === target) {
                matchedKey = provinceName;
                return false;
            }
        });

        return matchedKey;
    }

    function triggerFieldEvents(element) {
        if (!element) {
            return;
        }

        ['input', 'change', 'blur'].forEach(function (eventName) {
            element.dispatchEvent(new Event(eventName, { bubbles: true }));
        });
    }

    function setFieldValue(element, value) {
        if (!element) {
            return;
        }

        var normalizedValue = value || '';
        var prototype = element.tagName === 'SELECT' ? window.HTMLSelectElement.prototype : window.HTMLInputElement.prototype;
        var descriptor = prototype ? Object.getOwnPropertyDescriptor(prototype, 'value') : null;

        if (descriptor && descriptor.set) {
            descriptor.set.call(element, normalizedValue);
        } else {
            element.value = normalizedValue;
        }

        element.setAttribute('value', normalizedValue);
    }

    function getProvinceCityMap(provinceKey) {
        var entry = provinceKey && cityData && cityData[provinceKey] ? cityData[provinceKey] : {};

        if (Array.isArray(entry)) {
            var converted = {};
            entry.forEach(function (cityName) {
                converted[cityName] = '';
            });
            return converted;
        }

        return entry || {};
    }

    function resolvePostcode(cityMap, cityName) {
        var postcode = cityMap && cityMap[cityName] ? cityMap[cityName] : '';

        if (postcode) {
            return postcode;
        }

        var normalizedTarget = normalize(cityName);
        var matchedPostcode = '';

        $.each(cityMap || {}, function (name, zip) {
            if (normalize(name) === normalizedTarget) {
                matchedPostcode = zip || '';
                return false;
            }
        });

        return matchedPostcode;
    }

    function findField(prefix, fieldType) {
        var selectors = [];

        if (fieldType === 'state') {
            selectors = [
                '#' + prefix + '_state',
                '#' + prefix + '-state',
                '[name="' + prefix + '_state"]',
                '[name="' + prefix + '-state"]',
                'select[autocomplete$="address-level1"][id*="' + prefix + '"]',
                'select[autocomplete$="address-level1"][name*="' + prefix + '"]'
            ];
        } else if (fieldType === 'city') {
            selectors = [
                '#' + prefix + '_city',
                '#' + prefix + '-city',
                '[name="' + prefix + '_city"]',
                '[name="' + prefix + '-city"]',
                'input[autocomplete$="address-level2"][id*="' + prefix + '"]',
                'input[autocomplete$="address-level2"][name*="' + prefix + '"]',
                'select[autocomplete$="address-level2"][id*="' + prefix + '"]',
                'select[autocomplete$="address-level2"][name*="' + prefix + '"]'
            ];
        } else if (fieldType === 'barangay') {
            selectors = [
                '#' + prefix + '_address_1',
                '#' + prefix + '-address_1',
                '[name="' + prefix + '_address_1"]',
                '[name="' + prefix + '-address_1"]',
                'input[autocomplete$="address-line1"][id*="' + prefix + '"]',
                'select[autocomplete$="address-line1"][id*="' + prefix + '"]'
            ];
        } else if (fieldType === 'country') {
            selectors = [
                '#' + prefix + '_country',
                '#' + prefix + '-country',
                '[name="' + prefix + '_country"]',
                '[name="' + prefix + '-country"]',
                'select[autocomplete$="country"][id*="' + prefix + '"]',
                'select[autocomplete$="country"][name*="' + prefix + '"]'
            ];
        } else if (fieldType === 'postcode') {
            selectors = [
                '#' + prefix + '_postcode',
                '#' + prefix + '-postcode',
                '[name="' + prefix + '_postcode"]',
                '[name="' + prefix + '-postcode"]',
                'input[autocomplete$="postal-code"][id*="' + prefix + '"]',
                'input[autocomplete$="postal-code"][name*="' + prefix + '"]'
            ];
        }

        return $(selectors.join(','))
            .not('.flower-shop-generated-city-select')
            .not('.flower-shop-generated-barangay-select')
            .first();
    }

    function isPhilippinesSelected(prefix) {
        var $country = findField(prefix, 'country');

        if (!$country.length) {
            return true;
        }

        return String($country.val() || '').toUpperCase() === 'PH';
    }

    function ensureGeneratedCitySelect(prefix, $cityInput) {
        var $existing = $('select.flower-shop-generated-city-select[data-prefix="' + prefix + '"]').first();

        if ($existing.length) {
            return $existing;
        }

        var $provinceField = findField(prefix, 'state');
        var isBlockSelect = $provinceField.hasClass('wc-blocks-components-select__select');
        var fieldId = ($cityInput.attr('id') || (prefix + '-city')) + '-select';
        var labelText = 'City / Municipality';
        var classNames = [
            $cityInput.attr('class') || '',
            $provinceField.attr('class') || '',
            'flower-shop-generated-city-select'
        ].join(' ').replace(/\s+/g, ' ').trim();
        var $select = $('<select>', {
            id: fieldId,
            'class': classNames,
            'data-prefix': prefix,
            autocomplete: $cityInput.attr('autocomplete') || 'address-level2',
            'aria-invalid': 'false'
        });

        if (isBlockSelect) {
            labelText = $.trim($cityInput.closest('.wc-block-components-address-form__city').find('label').first().text()) || labelText;

            var $wrapper = $('<div>', {
                'class': 'wc-blocks-components-select flower-shop-generated-city-select-wrapper',
                'data-prefix': prefix
            });
            var $container = $('<div>', {
                'class': 'wc-blocks-components-select__container'
            });
            var $label = $('<label>', {
                'for': fieldId,
                'class': 'wc-blocks-components-select__label',
                text: labelText
            });
            var $icon = $('<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="wc-blocks-components-select__expand" aria-hidden="true" focusable="false"><path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path></svg>');

            $container.append($label).append($select).append($icon);
            $wrapper.append($container);
            $cityInput.after($wrapper);
        } else {
            if ($provinceField.length && window.getComputedStyle) {
                var provinceStyles = window.getComputedStyle($provinceField.get(0));

                $select.css({
                    backgroundImage: provinceStyles.backgroundImage,
                    backgroundPosition: provinceStyles.backgroundPosition,
                    backgroundRepeat: provinceStyles.backgroundRepeat,
                    backgroundSize: provinceStyles.backgroundSize,
                    paddingRight: provinceStyles.paddingRight
                });
            }

            $cityInput.after($select);
        }

        $cityInput.attr('type', 'hidden').hide();
        return $select;
    }

    function ensureGeneratedBarangaySelect(prefix, $barangayInput) {
        var $existing = $('select.flower-shop-generated-barangay-select[data-prefix="' + prefix + '"]').first();

        if ($existing.length) {
            return $existing;
        }

        var $provinceField = findField(prefix, 'state');
        var isBlockSelect = $provinceField.hasClass('wc-blocks-components-select__select');
        var fieldId = ($barangayInput.attr('id') || (prefix + '-address_1')) + '-select';
        var labelText = 'Barangay';
        var classNames = [
            $barangayInput.attr('class') || '',
            $provinceField.attr('class') || '',
            'flower-shop-generated-barangay-select'
        ].join(' ').replace(/\s+/g, ' ').trim();
        var $select = $('<select>', {
            id: fieldId,
            'class': classNames,
            'data-prefix': prefix,
            autocomplete: 'address-line1',
            'aria-invalid': 'false'
        });

        if (isBlockSelect) {
            labelText = $.trim($barangayInput.closest('.wc-block-components-address-form__address_1').find('label').first().text()) || labelText;

            var $wrapper = $('<div>', {
                'class': 'wc-blocks-components-select flower-shop-generated-barangay-select-wrapper',
                'data-prefix': prefix
            });
            var $container = $('<div>', {
                'class': 'wc-blocks-components-select__container'
            });
            var $label = $('<label>', {
                'for': fieldId,
                'class': 'wc-blocks-components-select__label',
                text: labelText
            });
            var $icon = $('<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="wc-blocks-components-select__expand" aria-hidden="true" focusable="false"><path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path></svg>');

            $container.append($label).append($select).append($icon);
            $wrapper.append($container);
            $barangayInput.after($wrapper);
        } else {
            $barangayInput.after($select);
        }

        $barangayInput.attr('type', 'hidden').hide();
        $barangayInput.closest('.wc-block-components-address-form__address_1').addClass('flower-shop-has-generated-barangay');

        return $select;
    }

    function clearBlocksValidationError(fieldName) {
        if (!fieldName) {
            return;
        }

        if (window.wp && window.wp.data) {
            try {
                var store = window.wp.data.dispatch('wc/store/validation');

                if (store && typeof store.clearValidationError === 'function') {
                    store.clearValidationError(fieldName);
                }
            } catch (e) {
                // validation store not available — silent fail
            }
        }
    }

    function syncCityValue($cityInput, value) {
        if (!$cityInput.length) {
            return;
        }

        var el = $cityInput.get(0);

        setFieldValue(el, value || '');
        triggerFieldEvents(el);

        if (value) {
            var fieldName = $cityInput.attr('name') || '';

            if (fieldName) {
                clearBlocksValidationError(fieldName);
            }

            $cityInput.attr('aria-invalid', 'false');
        }
    }

    function syncPostcodeValue($postcodeInput, value, prefix) {
        if (!$postcodeInput.length) {
            return;
        }

        var postcodeValue = value || '';

        if (prefix) {
            latestSelectedPostcodes[prefix] = postcodeValue;
        }

        setFieldValue($postcodeInput.get(0), postcodeValue);
        triggerFieldEvents($postcodeInput.get(0));
    }

    function syncBarangayValue($barangayInput, value) {
        if (!$barangayInput.length) {
            return;
        }

        var el = $barangayInput.get(0);

        setFieldValue(el, value || '');
        triggerFieldEvents(el);

        if (value) {
            var fieldName = $barangayInput.attr('name') || '';

            if (fieldName) {
                clearBlocksValidationError(fieldName);
            }

            $barangayInput.attr('aria-invalid', 'false');
        }
    }

    function resetAddressSelection(prefix) {
        var $cityField = findField(prefix, 'city');
        var $postcodeField = findField(prefix, 'postcode');
        var $barangayField = findField(prefix, 'barangay');
        var $citySelect = $('select.flower-shop-generated-city-select[data-prefix="' + prefix + '"]').first();
        var $barangaySelect = $('select.flower-shop-generated-barangay-select[data-prefix="' + prefix + '"]').first();

        latestSelectedCities[prefix] = '';
        latestSelectedPostcodes[prefix] = '';
        latestSelectedBarangays[prefix] = '';

        if ($citySelect.length) {
            $citySelect.val('');
        }

        if ($barangaySelect.length) {
            $barangaySelect.val('');
        }

        syncCityValue($cityField, '');
        syncPostcodeValue($postcodeField, '', prefix);
        syncBarangayValue($barangayField, '');
    }

    function repopulateBarangaySelect(prefix, shouldReset) {
        if (!barangayData) {
            return;
        }

        var $barangayField = findField(prefix, 'barangay');

        if (!$barangayField.length) {
            return;
        }

        var $citySelect = $('select.flower-shop-generated-city-select[data-prefix="' + prefix + '"]').first();
        var selectedCity = $citySelect.length ? $citySelect.val() : findField(prefix, 'city').val();
        var barangays = (selectedCity && barangayData[selectedCity]) ? barangayData[selectedCity] : [];
        var $barangaySelect = $barangayField.is('select.flower-shop-generated-barangay-select')
            ? $barangayField
            : ensureGeneratedBarangaySelect(prefix, $barangayField);
        var currentBarangay = shouldReset ? '' : ($barangaySelect.val() || latestSelectedBarangays[prefix] || $barangayField.val() || '');

        $barangaySelect.empty();
        $barangaySelect.append($('<option>', {
            value: '',
            text: selectBarangayText
        }));

        if (!barangays.length) {
            latestSelectedBarangays[prefix] = '';
            $barangaySelect.prop('disabled', true);
            syncBarangayValue($barangayField, '');
            return;
        }

        var matchedCurrent = false;

        $.each(barangays, function (_, barangayName) {
            var isSelected = normalize(currentBarangay) === normalize(barangayName);

            if (isSelected) {
                matchedCurrent = true;
            }

            $barangaySelect.append($('<option>', {
                value: barangayName,
                text: barangayName,
                selected: isSelected
            }));
        });

        var selectedBarangayValue = currentBarangay && matchedCurrent ? currentBarangay : '';

        $barangaySelect.val(selectedBarangayValue);
        $barangaySelect.prop('disabled', false);
        $barangaySelect.off('change.flowerShopBarangay').on('change.flowerShopBarangay', function () {
            var value = $(this).val() || '';

            latestSelectedBarangays[prefix] = value;
            syncBarangayValue($barangayField, value);
        });

        latestSelectedBarangays[prefix] = selectedBarangayValue;
        syncBarangayValue($barangayField, selectedBarangayValue);
    }

    function repopulateCitySelect(prefix, shouldReset) {
        if (!cityData) {
            return;
        }

        var $province = findField(prefix, 'state');
        var $cityField = findField(prefix, 'city');

        if (!$province.length || !$cityField.length) {
            return;
        }

        if (!isPhilippinesSelected(prefix)) {
            return;
        }

        var selectedProvinceCode = $province.val() || '';
        var provinceKey = resolveProvinceKey(selectedProvinceCode);
        var cityMap = getProvinceCityMap(provinceKey);
        var cityNames = Object.keys(cityMap);
        var $postcodeField = findField(prefix, 'postcode');
        var $citySelect = $cityField.is('select') ? $cityField : ensureGeneratedCitySelect(prefix, $cityField);
        var currentCity = shouldReset ? '' : ($citySelect.val() || latestSelectedCities[prefix] || $cityField.val() || $cityField.attr('value') || '');
        var matchedCurrent = false;

        $citySelect.empty();
        $citySelect.append($('<option>', {
            value: '',
            text: selectCityText
        }));

        if (!selectedProvinceCode || !cityNames.length) {
            latestSelectedCities[prefix] = '';
            latestSelectedPostcodes[prefix] = '';
            $citySelect.prop('disabled', true);
            syncCityValue($cityField, '');
            syncPostcodeValue($postcodeField, '', prefix);
            repopulateBarangaySelect(prefix, true);
            return;
        }

        $.each(cityNames, function (_, cityName) {
            var isSelected = normalize(currentCity) === normalize(cityName);

            if (isSelected) {
                matchedCurrent = true;
            }

            $citySelect.append($('<option>', {
                value: cityName,
                text: cityName,
                selected: isSelected
            }));
        });

        if (currentCity && !matchedCurrent && !shouldReset) {
            $citySelect.append($('<option>', {
                value: currentCity,
                text: currentCity,
                selected: true
            }));
        }

        var selectedCityValue = currentCity && (matchedCurrent || !shouldReset) ? currentCity : '';
        var resolvedInitialPostcode = selectedCityValue ? resolvePostcode(cityMap, selectedCityValue) : '';

        $citySelect.val(selectedCityValue);
        $citySelect.prop('disabled', false);
        $citySelect.off('change.flowerShopCity').on('change.flowerShopCity', function () {
            var selectedCity = $(this).val() || '';
            var resolvedPostcode = selectedCity ? resolvePostcode(cityMap, selectedCity) : '';

            latestSelectedCities[prefix] = selectedCity;
            latestSelectedBarangays[prefix] = '';
            syncCityValue($cityField, selectedCity);
            syncPostcodeValue($postcodeField, resolvedPostcode || (selectedCity ? latestSelectedPostcodes[prefix] || '' : ''), prefix);
            repopulateBarangaySelect(prefix, true);
        });

        if ($postcodeField.length) {
            $postcodeField.off('.flowerShopPostcode').on('input.flowerShopPostcode change.flowerShopPostcode', function () {
                latestSelectedPostcodes[prefix] = $(this).val() || '';
                $(this).attr('value', latestSelectedPostcodes[prefix]);
            });
        }

        $(document.body).trigger('wc-enhanced-select-init');
        latestSelectedCities[prefix] = selectedCityValue;
        syncCityValue($cityField, selectedCityValue);
        syncPostcodeValue($postcodeField, resolvedInitialPostcode || (selectedCityValue ? latestSelectedPostcodes[prefix] || '' : ''), prefix);
        repopulateBarangaySelect(prefix, false);
    }

    function initializeBarangayDropdowns(resetPrefix) {
        repopulateBarangaySelect('billing', !!resetPrefix && resetPrefix === 'billing');
        repopulateBarangaySelect('shipping', !!resetPrefix && resetPrefix === 'shipping');
    }

    function initializeAddressDropdowns() {
        repopulateCitySelect('billing', !!pendingResetPrefixes.billing);
        repopulateCitySelect('shipping', !!pendingResetPrefixes.shipping);
        scheduleBlockAddressReorder();
        pendingResetPrefixes = {};
    }

    function scheduleRefresh(resetPrefix) {
        if (resetPrefix) {
            pendingResetPrefixes[resetPrefix] = true;
        }

        if (refreshTimer) {
            window.clearTimeout(refreshTimer);
        }

        refreshTimer = window.setTimeout(function () {
            initializeAddressDropdowns();
        }, 150);
    }

    function bindEvents() {
        $(document.body)
            .on('change', '#billing_state, #shipping_state, #billing-state, #shipping-state, [name="billing_state"], [name="shipping_state"], [name="billing-state"], [name="shipping-state"]', function () {
                var fieldRef = String($(this).attr('id') || $(this).attr('name') || '');
                var prefix = fieldRef.indexOf('shipping') !== -1 ? 'shipping' : 'billing';

                resetAddressSelection(prefix);
                scheduleRefresh(prefix);
                scheduleBlockAddressReorder();
                $(document.body).trigger('update_checkout');
            })
            .on('change', 'select.flower-shop-generated-city-select', function () {
                var prefix = $(this).data('prefix') || 'billing';

                latestSelectedBarangays[prefix] = '';
                repopulateBarangaySelect(prefix, true);
                scheduleBlockAddressReorder();
            })
            .on('change', '.wc-block-components-checkbox input[type="checkbox"], input[type="checkbox"][name*="billing"][name*="shipping"], input[type="checkbox"][id*="billing"][id*="shipping"]', function () {
                // Block checkout may remount address fields when toggling "Use same address for billing".
                // Reinitialize both groups so city/barangay selects are recreated from text inputs.
                scheduleRefresh('billing');
                scheduleRefresh('shipping');
                scheduleBlockAddressReorder();
            })
            .on('click', '#shipping-method .wc-block-checkout__shipping-method-option, .wc-block-components-checkbox', function () {
                // Shipping method and some block checkboxes remount address inputs asynchronously.
                window.setTimeout(function () {
                    ensureAddressControlsReady();
                    scheduleBlockAddressReorder();
                }, 120);
            })
            .on('updated_checkout', function () {
                ensureAddressControlsReady();
                scheduleBlockAddressReorder();
            });
    }

    function loadCityData() {
        if (!addressDataUrl) {
            return;
        }

        $.getJSON(addressDataUrl)
            .done(function (response) {
                cityData = response || {};
                initializeAddressDropdowns();
            });
    }

    function loadBarangayData() {
        if (!barangayDataUrl) {
            return;
        }

        $.getJSON(barangayDataUrl)
            .done(function (response) {
                barangayData = response || {};
                initializeBarangayDropdowns();
            });
    }

    $(function () {
        installCheckoutPayloadSync();
        bindEvents();
        loadCityData();
        loadBarangayData();
        ensureAddressControlsReady();
        scheduleBlockAddressReorder();
    });
}(jQuery));
