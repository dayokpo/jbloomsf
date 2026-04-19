(function ($) {
    'use strict';

    var config = window.flowerShopCheckoutData || {};
    var provinceOptions = config.provinceOptions || {};
    var addressDataUrl = config.cityDataUrl || '';
    var selectCityText = (config.i18n && config.i18n.selectCity) || 'Select City / Municipality';
    var cityData = null;
    var refreshTimer = null;
    var pendingResetPrefixes = {};
    var latestSelectedCities = {};
    var latestSelectedPostcodes = {};

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

        return $(selectors.join(',')).not('.flower-shop-generated-city-select').first();
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

    function syncCityValue($cityInput, value) {
        if (!$cityInput.length) {
            return;
        }

        setFieldValue($cityInput.get(0), value || '');
        triggerFieldEvents($cityInput.get(0));
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

    function resetAddressSelection(prefix) {
        var $cityField = findField(prefix, 'city');
        var $postcodeField = findField(prefix, 'postcode');
        var $citySelect = $('select.flower-shop-generated-city-select[data-prefix="' + prefix + '"]').first();

        latestSelectedCities[prefix] = '';
        latestSelectedPostcodes[prefix] = '';

        if ($citySelect.length) {
            $citySelect.val('');
        }

        syncCityValue($cityField, '');
        syncPostcodeValue($postcodeField, '', prefix);
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
            var selectedCity = $(this).val();
            var resolvedPostcode = selectedCity ? resolvePostcode(cityMap, selectedCity) : '';

            latestSelectedCities[prefix] = selectedCity || '';
            syncCityValue($cityField, selectedCity);
            syncPostcodeValue($postcodeField, resolvedPostcode || (selectedCity ? latestSelectedPostcodes[prefix] || '' : ''), prefix);
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
    }

    function initializeAddressDropdowns() {
        repopulateCitySelect('billing', !!pendingResetPrefixes.billing);
        repopulateCitySelect('shipping', !!pendingResetPrefixes.shipping);
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
                $(document.body).trigger('update_checkout');
            })
            .on('updated_checkout', function () {
                scheduleRefresh();
            });

        if (window.MutationObserver) {
            new MutationObserver(function () {
                scheduleRefresh();
            }).observe(document.body, {
                childList: true,
                subtree: true
            });
        }
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

    $(function () {
        bindEvents();
        loadCityData();
    });
}(jQuery));