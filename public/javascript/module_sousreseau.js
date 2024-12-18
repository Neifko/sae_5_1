let nbTotHosts = 0;
let totalHostsAvailable = 0;
let verifHosts = true;

function applySubnets() {
    const numSubnets = parseInt(document.getElementById('nb_subnets').value.trim());

    // Vérifier si le nombre de sous-réseaux est valide
    if (isNaN(numSubnets) || numSubnets <= 0) {
        alert("Veuillez entrer un nombre valide de sous-réseaux.");
        return;
    }
    //test

    // Récupérer le conteneur où les nouveaux formulaires seront ajoutés
    const subnetFormsContainer = document.getElementById('subnetFormsContainer');
    subnetFormsContainer.innerHTML = ''; // Réinitialiser les formulaires existants

    // Générer un formulaire pour chaque sous-réseau
    for (let i = 1; i <= numSubnets; i++) {
        const subnetForm = document.createElement('div');
        subnetForm.classList.add('subnet-form');
        subnetForm.innerHTML = `
                    <h3>Sous-réseau ${i}</h3>
                    <label for="name_subnet${i}">Nom du sous-réseau</label>
                    <input type="text" id="name_subnet${i}" name="name_subnet${i}" value="${i}" required>

                    <label for="nb_machines${i}">Nombre de machines</label>
                    <input type="text" id="nb_machines${i}" name="nb_machines${i}"  required>
                `;
        subnetFormsContainer.appendChild(subnetForm);
    }

    // Ajouter un bouton "Calculer" après tous les sous-formulaires
    const calculateButton = document.createElement('button');
    calculateButton.type = 'button';
    calculateButton.innerText = 'Calculer';
    calculateButton.onclick = function() {
        calculateSubnets(); // Appeler la fonction de calcul
    };

    // Ajouter le bouton à la fin du conteneur des sous-formulaires
    subnetFormsContainer.appendChild(calculateButton);
}

function calculateSubnets() {
    const ip_address = document.getElementById('ip_address').value.trim();
    const cidr = document.getElementById('cidr').value.trim();

    calculateTotalHosts(cidr);

    if (!validateIP(ip_address)) {
        window.alert("Adresse IP invalide");
        return;
    } else if (!validateCIDR(cidr)) {
        window.alert("Masque invalide");
        return;
    }

    const numSubnets = parseInt(document.getElementById('nb_subnets').value.trim());
    const dataSubnet = [];

    // Adresse réseau initiale en binaire
    let networkBinary = calculate_networkAddress(ipToBinary(ip_address), ipToBinary(getSubnetMask(cidr)));

    let tableCroissant = [];
    for (let i = 1; i <= numSubnets; i++) {
        const nameSubnet = document.getElementById(`name_subnet${i}`);
        const nbMachines = document.getElementById(`nb_machines${i}`);
        tableCroissant.push({
            name: nameSubnet.value.trim(),
            machines: parseInt(nbMachines.value.trim())
        });
    }

    tableCroissant.sort((a, b) => b.machines - a.machines);

    for (let i = 0; i < tableCroissant.length; i++) {
        const nameSubnet = tableCroissant[i].name;
        const nbMachines = tableCroissant[i].machines;

        if (!nameSubnet || isNaN(nbMachines) || parseInt(nbMachines) <= 0) {
            window.alert(`Nombre de machines invalide pour le sous-réseau ${i}`);
            return;
        }

        const name = nameSubnet;
        const machines = parseInt(nbMachines);

        nbTotHosts += machines;

        // Calculer le nombre minimal de bits nécessaires pour les hôtes
        const bitsForHosts = calculateBitsNeeded(machines);
        const cidrOptimal = 32 - bitsForHosts;

        const subnetMask = getSubnetMask(cidrOptimal);
        const hostsAvailable = Math.pow(2,calculateBitsNeeded(machines)) - 2;

        if (machines > hostsAvailable) {
            window.alert(`Le sous-réseau ${nameSubnet}  ne peut pas contenir autant de machines. Hôtes disponibles : ${hostsAvailable}`);
            return;
        }

        // Calculer l'adresse réseau actuelle
        const currentNetworkAddress = binaryToIp(networkBinary);

        // Calculer l'adresse de broadcast
        const broadcastBinary = calculate_broadcastAddress(networkBinary, ipToBinary(subnetMask));
        const broadcastAddress = binaryToIp(broadcastBinary);

        // Calculer la plage d'adresses utilisables
        const usableRange = getUsableRange(networkBinary, broadcastBinary);

        // Ajouter les données du sous-réseau
        dataSubnet.push({
            name,
            hostsNeeded: machines,
            hostsAvailable,
            unusedHosts: hostsAvailable - machines,
            networkAddress: currentNetworkAddress,
            cidr: cidrOptimal,
            subnetMask,
            usableRange,
            broadcastAddress
        });

        // Mettre à jour l'adresse réseau pour le prochain sous-réseau
        const networkIncrement = Math.pow(2, bitsForHosts); // Taille du sous-réseau
        const nextNetworkDecimal = parseInt(networkBinary, 2) + networkIncrement;
        networkBinary = nextNetworkDecimal.toString(2).padStart(32, '0'); // Nouvelle adresse réseau en binaire
    }

    if (nbTotHosts > totalHostsAvailable){
        verifHosts = false;
    }

    displaySubnets(dataSubnet);
}


// Fonction pour afficher les sous-réseaux et les informations réseau
function displaySubnets(dataSubnet) {
    const resultContainer = document.getElementById('result');
    resultContainer.innerHTML = ""; // Réinitialiser les résultats précédents

    // Créer un tableau pour afficher les résultats
    const table = document.createElement('table');

    // Ajouter une ligne pour le nombre total de machines disponibles sur le réseau
    const totalHostsRow = document.createElement('tr');
    totalHostsRow.innerHTML = `
        <td colspan="9"><strong>Nombre total de machines disponibles sur le réseau : ${totalHostsAvailable}</strong></td>
    `;
    table.appendChild(totalHostsRow);

    // Ajouter une ligne pour le nombre total de machines demandées
    const totalRequestedRow = document.createElement('tr');
    totalRequestedRow.innerHTML = `
        <td colspan="9"><strong>Nombre total de machines demandées : ${nbTotHosts}</strong></td>
    `;
    table.appendChild(totalRequestedRow);

    if (verifHosts === false){
        const totalRequestedRow = document.createElement('tr');
        totalRequestedRow.innerHTML = `
        <td colspan="9"><strong>Les nombres de machines entrés ne sont pas adaptés à ce réseau. Voici une solution possible : </strong></td>
    `;
        table.appendChild(totalRequestedRow);
    }

    // Ajouter les autres colonnes du tableau
    table.innerHTML += `
        <tr>
            <th>Nom du sous-réseau</th>
            <th>Machines nécessaires</th>
            <th>Machines disponibles</th>
            <th>Machines non utilisées</th>
            <th>Network Address</th>
            <th>Slash</th>
            <th>Mask</th>
            <th>Range des adresses</th>
            <th>Adresse de broadcast</th>
        </tr>
    `;

    // Ajouter les sous-réseaux au tableau
    dataSubnet.forEach((subnet) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${subnet.name}</td>
            <td>${subnet.hostsNeeded}</td>
            <td>${subnet.hostsAvailable}</td>
            <td>${subnet.unusedHosts}</td>
            <td>${subnet.networkAddress}</td>
            <td>/${subnet.cidr}</td>
            <td>${subnet.subnetMask}</td>
            <td>${subnet.usableRange.start} - ${subnet.usableRange.end}</td>
            <td>${subnet.broadcastAddress}</td>
        `;
        table.appendChild(row); // Ajouter la ligne au tableau
    });

    resultContainer.appendChild(table);
    nbTotHosts = 0;
    totalHostsAvailable = 0;
}

// Fonction pour valider l'adresse IP
function validateIP(ip){
    const regex = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
    return regex.test(ip);
}

// Fonction pour valider le CIDR
function validateCIDR(cidr) {
    const cidrInt = parseInt(cidr, 10);
    if (cidrInt <= 0) {
        return false;
    } else if (cidrInt > 32) {
        return false;
    }
    return true;
}

// Convertir l'adresse IP en binaire
function ipToBinary(ip) {
    return ip.split('.').map(num => {
        let bin = parseInt(num, 10).toString(2);
        return bin.padStart(8, '0');
    }).join('');
}

// Convertir une adresse binaire en IP
function binaryToIp(binary) {
    return binary.match(/.{8}/g).map(bin => parseInt(bin, 2)).join('.');
}

// Fonction pour obtenir le masque de sous-réseau à partir du CIDR
function getSubnetMask(cidr) {
    // Vérifier que cidr est un entier valide entre 0 et 32
    const cidrInt = parseInt(cidr, 10);
    if (isNaN(cidrInt) || cidrInt < 0 || cidrInt > 32) {
        console.log(cidrInt);
        throw new Error("CIDR invalide. Il doit être un entier entre 0 et 32.");

    }

    // Générer le masque binaire en fonction du CIDR
    const maskBinary = '1'.repeat(cidrInt) + '0'.repeat(32 - cidrInt);
    return binaryToIp(maskBinary);
}

// Calcul de l'adresse réseau
function calculate_networkAddress(ipBinary, maskBinary) {
    let networkBinary = '';
    for (let i = 0; i < ipBinary.length; i++) {
        networkBinary += ipBinary[i] === '1' && maskBinary[i] === '1' ? '1' : '0';
    }
    return networkBinary;
}

// Calcul de l'adresse de broadcast
function calculate_broadcastAddress(ipBinary, maskBinary) {
    let broadcastBinary = '';
    for (let i = 0; i < ipBinary.length; i++) {
        broadcastBinary += maskBinary[i] === '1' ? ipBinary[i] : '1';
    }
    return broadcastBinary;
}

// Obtenir la plage d'adresses utilisables
function getUsableRange(networkBinary, broadcastBinary) {
    const startBinary = (parseInt(networkBinary, 2) + 1).toString(2).padStart(32, '0');
    const endBinary = (parseInt(broadcastBinary, 2) - 1).toString(2).padStart(32, '0');
    return {
        start: binaryToIp(startBinary),
        end: binaryToIp(endBinary)
    };
}

function calculateBitsNeeded(machines){
    let i = 0;
    let cumul = 0;
    while (cumul < machines + 2){
        cumul += Math.pow(2, i);
        console.log(cumul + " " + i);
        i++;
    }
    return i;
}

function calculateTotalHosts(cidr) {
    const bitsForHosts = 32 - parseInt(cidr, 10); // Bits pour les hôtes
    totalHostsAvailable = Math.pow(2, bitsForHosts) - 2; // 2^bits - 2
}