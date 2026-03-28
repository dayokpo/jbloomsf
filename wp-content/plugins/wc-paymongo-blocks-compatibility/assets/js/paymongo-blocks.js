(function () {
    if (!window.wc || !window.wc.wcBlocksRegistry || !window.wc.wcSettings || !window.wp || !window.wp.element) {
        return;
    }

    var registerPaymentMethod = window.wc.wcBlocksRegistry.registerPaymentMethod;
    var getSetting = window.wc.wcSettings.getSetting;
    var createElement = window.wp.element.createElement;
    var useEffect = window.wp.element.useEffect;
    var useState = window.wp.element.useState;
    var decodeEntities = window.wp.htmlEntities && window.wp.htmlEntities.decodeEntities
        ? window.wp.htmlEntities.decodeEntities
        : function (value) { return value; };

    var gatewayIds = [
        'paymongo',
        'paymongo_card_installment',
        'paymongo_gcash',
        'paymongo_grab_pay',
        'paymongo_paymaya',
        'paymongo_atome',
        'paymongo_bpi',
        'paymongo_unionbank',
        'paymongo_billease'
    ];

    var methodIdField = 'cynder_paymongo_method_id';
    var paymongoEndpoint = 'https://api.paymongo.com/v1/payment_methods';
    var debug = true;
    var allPaymentMethodData = getSetting('paymentMethodData', {});

    function log() {
        if (!debug || !window.console || !window.console.log) {
            return;
        }

        var args = Array.prototype.slice.call(arguments);
        args.unshift('[WCPMBC]');
        window.console.log.apply(window.console, args);
    }

    function mapBillingData(rawBilling) {
        var billing = rawBilling || {};

        var firstName = billing.first_name || billing.firstName || '';
        var lastName = billing.last_name || billing.lastName || '';
        var company = billing.company || '';
        var line1 = billing.address_1 || billing.address1 || '';
        var line2 = billing.address_2 || billing.address2 || '';
        var city = billing.city || '';
        var state = billing.state || '';
        var country = billing.country || '';
        var postcode = billing.postcode || billing.postalCode || '';
        var email = billing.email || '';
        var phone = billing.phone || '';

        var fullName = (firstName + ' ' + lastName).trim();
        if (company) {
            fullName = fullName ? fullName + ' - ' + company : company;
        }

        return {
            address: {
                line1: line1,
                line2: line2,
                city: city,
                state: state,
                country: country,
                postal_code: postcode
            },
            name: fullName,
            email: email,
            phone: phone
        };
    }

    function normalizeErrorMessage(errorObject) {
        var fallbackMessage = 'Unable to process card payment with PayMongo. Please verify your card details and try again.';
        if (!errorObject || !errorObject.errors || !Array.isArray(errorObject.errors) || !errorObject.errors.length) {
            return fallbackMessage;
        }

        return errorObject.errors
            .map(function (errorItem) {
                return errorItem.detail || fallbackMessage;
            })
            .join(' ');
    }

    function createCardPaymentMethod(payload, publicKey) {
        return fetch(paymongoEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': 'Basic ' + window.btoa(publicKey)
            },
            body: JSON.stringify({
                data: {
                    attributes: payload
                }
            })
        }).then(function (response) {
            return response.json().then(function (body) {
                if (!response.ok) {
                    throw body;
                }

                return body;
            });
        });
    }

    function GenericContent(args) {
        return createElement(
            'div',
            { className: 'wc-paymongo-block-description' },
            args.description || ''
        );
    }

    function CardContent(props) {
        var settings = props.settings;
        var activePaymentMethod = props.activePaymentMethod;
        var eventRegistration = props.eventRegistration;
        var emitResponse = props.emitResponse || {};
        var billingData = props.billing || {};

        var _useState = useState('');
        var cardNumber = _useState[0];
        var setCardNumber = _useState[1];

        var _useState2 = useState('');
        var expiry = _useState2[0];
        var setExpiry = _useState2[1];

        var _useState3 = useState('');
        var cvc = _useState3[0];
        var setCvc = _useState3[1];

        var successType = emitResponse.responseTypes ? emitResponse.responseTypes.SUCCESS : 'success';
        var errorType = emitResponse.responseTypes ? emitResponse.responseTypes.ERROR : 'error';

        useEffect(function () {
            if (!eventRegistration || !eventRegistration.onPaymentSetup) {
                return function () {};
            }

            var unsubscribe = eventRegistration.onPaymentSetup(function () {
                if (activePaymentMethod !== 'paymongo') {
                    return {
                        type: successType,
                        meta: {
                            paymentMethodData: {}
                        }
                    };
                }

                var cleanCardNumber = String(cardNumber || '').replace(/\s+/g, '');
                var expiryParts = String(expiry || '').split('/').map(function (part) {
                    return part.trim();
                });
                var expMonth = parseInt(expiryParts[0], 10);
                var expYear = parseInt(expiryParts[1], 10);

                if (!cleanCardNumber || !expMonth || !expYear || !cvc) {
                    return {
                        type: errorType,
                        message: 'Please complete your card details.',
                        messageContext: 'wc/payments'
                    };
                }

                if (!settings.publicKey) {
                    return {
                        type: errorType,
                        message: 'PayMongo public key is not configured.',
                        messageContext: 'wc/payments'
                    };
                }

                var payload = {
                    type: 'card',
                    details: {
                        card_number: cleanCardNumber,
                        exp_month: expMonth,
                        exp_year: expYear,
                        cvc: cvc
                    },
                    billing: mapBillingData(billingData.billingData || billingData)
                };

                return createCardPaymentMethod(payload, settings.publicKey)
                    .then(function (response) {
                        var paymentMethodId = response && response.data ? response.data.id : '';
                        if (!paymentMethodId) {
                            return {
                                type: errorType,
                                message: 'Unable to create PayMongo payment method.',
                                messageContext: 'wc/payments'
                            };
                        }

                        var paymentData = {};
                        paymentData[methodIdField] = paymentMethodId;

                        return {
                            type: successType,
                            meta: {
                                paymentMethodData: paymentData
                            }
                        };
                    })
                    .catch(function (errorBody) {
                        return {
                            type: errorType,
                            message: normalizeErrorMessage(errorBody),
                            messageContext: 'wc/payments'
                        };
                    });
            });

            return unsubscribe;
        }, [
            activePaymentMethod,
            eventRegistration,
            successType,
            errorType,
            cardNumber,
            expiry,
            cvc,
            billingData,
            settings.publicKey
        ]);

        return createElement(
            'div',
            { className: 'wc-paymongo-card-fields' },
            createElement(
                'p',
                null,
                settings.description || 'Enter your card details to continue with PayMongo.'
            ),
            createElement(
                'p',
                null,
                createElement('label', { htmlFor: 'paymongo_ccNo' }, 'Card Number'),
                createElement('input', {
                    id: 'paymongo_ccNo',
                    className: 'paymongo_ccNo',
                    type: 'text',
                    autoComplete: 'cc-number',
                    value: cardNumber,
                    onChange: function (event) { setCardNumber(event.target.value); }
                })
            ),
            createElement(
                'p',
                null,
                createElement('label', { htmlFor: 'paymongo_expdate' }, 'Expiry Date (MM / YY)'),
                createElement('input', {
                    id: 'paymongo_expdate',
                    className: 'paymongo_expdate',
                    type: 'text',
                    autoComplete: 'cc-exp',
                    value: expiry,
                    onChange: function (event) { setExpiry(event.target.value); }
                })
            ),
            createElement(
                'p',
                null,
                createElement('label', { htmlFor: 'paymongo_cvv' }, 'Card Code (CVC)'),
                createElement('input', {
                    id: 'paymongo_cvv',
                    className: 'paymongo_cvv',
                    type: 'password',
                    autoComplete: 'cc-csc',
                    value: cvc,
                    onChange: function (event) { setCvc(event.target.value); }
                })
            )
        );
    }

    function DisabledInstallmentContent() {
        return createElement(
            'div',
            { className: 'wc-paymongo-installment-disabled' },
            'PayMongo Card Installment is not currently supported in Checkout Block. Use the classic checkout shortcode to accept installment payments.'
        );
    }

    gatewayIds.forEach(function (gatewayId) {
        var settings = getSetting(gatewayId + '_data', allPaymentMethodData[gatewayId] || {});
        log('Loaded settings for gateway', gatewayId, settings);
        if (!settings || !Object.keys(settings).length) {
            log('Skipping gateway due to missing settings payload', gatewayId);
            return;
        }

        var labelText = decodeEntities(settings.title || gatewayId);

        var content = function () {
            return createElement(GenericContent, {
                description: settings.description
            });
        };

        var edit = content;
        var canMakePayment = function () {
            return true;
        };

        if (settings.isCardMethod) {
            content = function (props) {
                return createElement(CardContent, {
                    settings: settings,
                    activePaymentMethod: props.activePaymentMethod,
                    billing: props.billing,
                    eventRegistration: props.eventRegistration,
                    emitResponse: props.emitResponse
                });
            };
            edit = content;
        }

        if (settings.isInstallmentMethod) {
            content = DisabledInstallmentContent;
            edit = DisabledInstallmentContent;
            canMakePayment = function () {
                return false;
            };
        }

        registerPaymentMethod({
            name: gatewayId,
            label: createElement('span', null, labelText),
            ariaLabel: labelText,
            content: createElement(content),
            edit: createElement(edit),
            canMakePayment: canMakePayment,
            supports: {
                features: settings.supports || ['products']
            }
        });

        log('Registered payment method', gatewayId);
    });
})();
