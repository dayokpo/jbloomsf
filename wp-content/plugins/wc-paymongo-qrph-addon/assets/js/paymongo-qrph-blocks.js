(function () {
    if (!window.wc || !window.wc.wcBlocksRegistry || !window.wc.wcSettings || !window.wp || !window.wp.element) {
        return;
    }

    var registerPaymentMethod = window.wc.wcBlocksRegistry.registerPaymentMethod;
    var getSetting = window.wc.wcSettings.getSetting;
    var createElement = window.wp.element.createElement;
    var useEffect = window.wp.element.useEffect;
    var decodeEntities = window.wp.htmlEntities && window.wp.htmlEntities.decodeEntities
        ? window.wp.htmlEntities.decodeEntities
        : function (value) { return value; };

    var settings = getSetting('paymongo_qrph_data', (getSetting('paymentMethodData', {}) || {}).paymongo_qrph || {});
    if (!settings || !Object.keys(settings).length) {
        return;
    }

    var labelText = decodeEntities(settings.title || 'QR Ph via PayMongo');

    function Content(props) {
        var activePaymentMethod = props.activePaymentMethod;
        var eventRegistration = props.eventRegistration;
        var emitResponse = props.emitResponse || {};

        var successType = emitResponse.responseTypes ? emitResponse.responseTypes.SUCCESS : 'success';

        useEffect(function () {
            if (!eventRegistration || !eventRegistration.onPaymentSetup) {
                return function () {};
            }

            var unsubscribe = eventRegistration.onPaymentSetup(function () {
                if (activePaymentMethod !== 'paymongo_qrph') {
                    return {
                        type: successType,
                        meta: {
                            paymentMethodData: {}
                        }
                    };
                }

                return {
                    type: successType,
                    meta: {
                        paymentMethodData: {}
                    }
                };
            });

            return unsubscribe;
        }, [activePaymentMethod, eventRegistration, successType]);

        return createElement('div', null, settings.description || 'Scan and pay using QR Ph supported banks and e-wallets.');
    }

    registerPaymentMethod({
        name: 'paymongo_qrph',
        label: createElement('span', null, labelText),
        ariaLabel: labelText,
        content: createElement(Content),
        edit: createElement(Content),
        canMakePayment: function () { return true; },
        supports: {
            features: settings.supports || ['products']
        }
    });
})();
