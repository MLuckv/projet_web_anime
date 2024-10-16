class SelectAnime extends HTMLSelectElement {
    connectedCallback() {
        if (this.getAttribute('choicesBinded')) {
            return;
        }
        this.setAttribute('choicesBinded', true);

        const params = {
            persist: false,
            plugins: {},
            closeAfterSelect: true,
            hideSelected: true,
            searchField: this.dataset.label || 'nom',
        };

        if (this.tagName === 'SELECT') {
            params.allowEmptyOption = true;
            params.no_backspace_delete = {};
            params.dropdown_input = {};
            params.plugins_remove_button = {
                title: "Supp anime"
            };
        }

        if (this.dataset.remove) {
            params.valueField = this.dataset.value;
            params.labelField = this.dataset.label;
            params.labelSearch = this.dataset.search;
            params.load = async (query, callback) => {
                try {
                    const response = await fetch(`${this.dataset.remove}?q=${encodeURIComponent(query)}`);
                    if (response.ok) {
                        const data = await response.json();
                        callback(data);
                    } else {
                        console.error("Erreur de chargement des données :", response.statusText);
                        callback();
                    }
                } catch (error) {
                    console.error("Erreur de requête :", error);
                    callback();
                }
            };
        }

        if (this.dataset.create) {
            params.create = true;
        }

        const selectElement = document.createElement('select');

        Array.from(this.attributes).forEach(attr => {
            selectElement.setAttribute(attr.name, attr.value);
        });

        while (this.firstChild) {
            selectElement.appendChild(this.firstChild);
        }

        this.parentNode.replaceChild(selectElement, this);

        this.widget = new TomSelect(selectElement, params);

        // Redirection vers la page de détail de l'anime sélectionné
        this.widget.on('item_add', (value) => {
            const selectedAnime = this.widget.options[value];

            if (selectedAnime) {
                const uniqueId = Math.random().toString(36).substring(2, 7);
                const slug = `${selectedAnime.id}-${uniqueId}`;
                window.location.href = `/anime/${slug}`;
            }
        });
    }

    disconnectedCallback() {
        if (this.widget) {
            this.widget.destroy();
        }
    }
}

// Enregistrer l'élément personnalisé dans le DOM
customElements.define('select-anime', SelectAnime, { extends: 'select' });
