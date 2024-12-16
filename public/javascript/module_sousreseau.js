function applySubnets() {
    const numSubnets = parseInt(document.getElementById('subnets').value.trim());

    // Vérifier si le nombre de sous-réseaux est valide
    if (isNaN(numSubnets) || numSubnets <= 0) {
        alert("Veuillez entrer un nombre valide de sous-réseaux.");
        return;
    }

    // Récupérer le conteneur où les nouveaux formulaires seront ajoutés
    const subnetFormsContainer = document.getElementById('subnetFormsContainer');
    subnetFormsContainer.innerHTML = ''; // Réinitialiser les formulaires existants

    // Générer un formulaire pour chaque sous-réseau
    for (let i = 1; i <= numSubnets; i++) {
        const subnetForm = document.createElement('div');
        subnetForm.classList.add('subnet-form');
        subnetForm.innerHTML = `
                    <h3>Sous-réseau ${i}</h3>
                    <label for="subnetIP${i}">Adresse IP de sous-réseau :</label>
                    <input type="text" id="subnetIP${i}" name="subnetIP${i}" placeholder="192.168.1.0" required>

                    <label for="subnetCIDR${i}">CIDR du sous-réseau :</label>
                    <input type="text" id="subnetCIDR${i}" name="subnetCIDR${i}" placeholder="/24" required>

                    <label for="subnetSize${i}">Taille du sous-réseau :</label>
                    <input type="number" id="subnetSize${i}" name="subnetSize${i}" placeholder="256" required>
                `;
        subnetFormsContainer.appendChild(subnetForm);
    }
}