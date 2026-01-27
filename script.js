function chooseGiftTier(amount) {
    initGiftForm(amount);
}

function initGiftForm(amount) {

    const giftformSection = document.getElementById('gift-form-section');
    giftformSection.classList.remove('hidden');
    const amountInput = document.getElementById('gift-amount');
    amountInput.value = amount;
    const nameInput = document.getElementById('supporter-name');
    const messageInput = document.getElementById('supporter-message');
    const countrySelect = document.getElementById('country-code');
    const phoneInput = document.getElementById('mobile-number');
    const button = document.getElementById('gift-button');
    // const statusBanner = document.getElementById('status-banner');

    if (!amountInput || !nameInput || !button) {
        return;
    }

    const getCountryMeta = () => {
        if (!countrySelect) {
            return { dial: '', currency: 'USD', country: '' };
        }
        const option = countrySelect.selectedOptions[0];
        return {
            dial: option?.dataset.dial || '',
            currency: option?.dataset.currency || 'USD',
            country: option?.value || ''
        };
    };

    const buildE164Number = () => {
        if (!phoneInput) {
            return '';
        }
        const digits = phoneInput.value.replace(/\D/g, '');
        const { dial } = getCountryMeta();
        if (!digits || !dial) {
            return '';
        }
        return `${dial}${digits}`;
    };

    const flagPhoneInput = () => {
        if (!phoneInput) {
            return;
        }
        phoneInput.classList.add('ring-2', 'ring-rose-300');
        setTimeout(() => {
            phoneInput.classList.remove('ring-2', 'ring-rose-300');
        }, 1500);
    };

    const setButtonState = (state) => {
        if (state === 'processing') {
            button.disabled = true;
            button.innerHTML = '<span class="mr-2 inline-block h-3 w-3 animate-spin rounded-full border-2 border-slate-900 border-r-transparent"></span>Traitement en cours';
        } else {
            button.disabled = false;
            button.textContent = 'Envoyer le cadeau';
        }
    };

    button.addEventListener('click', () => {
        const amount = Math.max(5, Number(amountInput.value) || 0);
        const e164Number = buildE164Number();
        if (!e164Number) {
            flagPhoneInput();
            return;
        }

        const { dial, currency, country } = getCountryMeta();

        const formData = {
            amount,
            name: nameInput.value.trim() || 'Superfan anonyme',
            message: messageInput.value.trim(),
            phone: e164Number,
        };

        setButtonState('processing');

        // call api here


        // redirection vers la page d'attente après initialisation
        const transactionID = 0;
        window.location.href = 'pending.html?transId=' + transactionID;

    });
}