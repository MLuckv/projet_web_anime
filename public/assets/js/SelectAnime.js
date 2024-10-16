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
            valueField: 'id',   // Assurez-vous que 'id' est utilisé pour la valeur
            labelField: 'Nom',  // Assurez-vous que 'Nom' est utilisé pour l'affichage
            searchField: 'Nom'  // Définissez le champ de recherche si ce n’est pas encore fait
        };

        if (this.dataset.remove) {
            params.load = async (query, callback) => {
                try {
                    const response = await fetch(`${this.dataset.remove}?q=${encodeURIComponent(query)}`);
                    if (response.ok) {
                        const data = await response.json();

                        console.log("Données reçues :", data); // Log pour vérifier les données

                        // Pas besoin de transformation, les données sont déjà prêtes pour TomSelect
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

        // Instancier TomSelect
        this.widget = new TomSelect(selectElement, params);
    }

    disconnectedCallback() {
        if (this.widget) {
            this.widget.destroy();
        }
    }
}

// Enregistrer l'élément personnalisé dans le DOM
customElements.define('select-anime', SelectAnime, { extends: 'select' });
