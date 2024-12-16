function applySubnets() {
    const numSubnets = parseInt(document.getElementById('nb_subnets').value.trim());

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

function calculateSubnets(){
    const ip_address = document.getElementById('ip_address').value.trim();
    const cidr = document.getElementById('cidr').value.trim();

    if (!validateIP(ip_address)){
        window.alert("Adresse IP invalide");
        return;
    } else if (!validateCIDR(cidr)){
        window.alert("Masque invalide");
        return;
    }

    // Calcul du masque
    const subnetMask = getSubnetMask(cidr);

    // Convertir l'adresse IP et le masque en binaire
    const ipBinary = ipToBinary(ip_address);
    const maskBinary = ipToBinary(subnetMask);

    // Calcul de l'adresse réseau
    let networkBinary = calculate_networkAddress(ipBinary, maskBinary);
    let networkAddress = binaryToIp(networkBinary);

    // Calcul de l'adresse de broadcast
    let broadcastBinary = calculate_broadcastAddress(ipBinary, maskBinary);
    let broadcastAddress = binaryToIp(broadcastBinary);

    // Plage d'adresses utilisables
    let usableRange = getUsableRange(networkBinary, broadcastBinary);

    const numSubnets = parseInt(document.getElementById('nb_subnets').value.trim());

    const dataSubnet = [];
    let networkIncrement = Math.pow(2, (32 - parseInt(cidr))) - 2; // Pour l'incrémentation des adresses réseau
    for (let i = 1; i <= numSubnets; i++) {
        const nameSubnet = document.getElementById(`name_subnet${i}`);
        const nbMachines = document.getElementById(`nb_machines${i}`);

        if (!nameSubnet || nbMachines <= 0) {
            window.alert(`Nombre de machines invalide pour le sous-réseau ${i}`);
            return;
        }

        const name = nameSubnet.value.trim();
        const machines = parseInt(nbMachines.value.trim());
        const hostsAvailable = calculateHostsAvailable(cidr);

        if (machines > hostsAvailable) {
            window.alert(`Le sous-réseau ${i} ne peut pas contenir autant de machines. Hôtes disponibles: ${hostsAvailable}`);
            return;
        }

        const hostsNeeded = machines;
        const unusedHosts = hostsAvailable - hostsNeeded;

        // Ajouter les données du sous-réseau dans un tableau
        dataSubnet.push({
            name,
            hostsNeeded,
            hostsAvailable,
            unusedHosts,
            networkAddress,
            cidr,
            subnetMask,
            usableRange,
            broadcastAddress
        });

        // Mettre à jour l'adresse réseau pour le prochain sous-réseau
        const networkDecimal = parseInt(networkBinary, 2);
        networkBinary = (networkDecimal + networkIncrement).toString(2).padStart(32, '0');
        networkAddress = binaryToIp(networkBinary);

        // Calculer la nouvelle adresse de broadcast et la plage utilisable
        broadcastBinary = calculate_broadcastAddress(networkBinary, maskBinary);
        broadcastAddress = binaryToIp(broadcastBinary);
        usableRange = getUsableRange(networkBinary, broadcastBinary);
    }

    displaySubnets(dataSubnet);
}

// Fonction pour afficher les sous-réseaux et les informations réseau
function displaySubnets(dataSubnet) {
    const resultContainer = document.getElementById('result');
    resultContainer.innerHTML = ""; // Réinitialiser les résultats précédents

    const table = document.createElement('table');
    table.innerHTML = `
        <tr>
            <th>Name</th>
            <th>Hosts Needed</th>
            <th>Hosts Available</th>
            <th>Unused Hosts</th>
            <th>Network Address</th>
            <th>Slash</th>
            <th>Mask</th>
            <th>Usable Range</th>
            <th>Broadcast</th>
        </tr>
    `;

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
    const maskBinary = '1'.repeat(cidr) + '0'.repeat(32 - cidr);
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
    const startBinary = networkBinary.slice(0, -1) + '1';  // Première adresse utilisable
    const endBinary = broadcastBinary.slice(0, -1) + '0';   // Dernière adresse utilisable
    return {
        start: binaryToIp(startBinary),
        end: binaryToIp(endBinary)
    };
}

// Fonction pour calculer le nombre d'hôtes disponibles par sous-réseau en fonction du CIDR
function calculateHostsAvailable(cidr) {
    const bitsForHosts = 32 - parseInt(cidr, 10);
    return Math.pow(2, bitsForHosts) - 2; // Soustraction des adresses réseau et broadcast
}

// Fonction pour incrémenter l'adresse réseau pour le prochain sous-réseau
function incrementNetworkAddress(networkBinary, cidr) {
    const bitsForHosts = 32 - parseInt(cidr, 10);
    const increment = Math.pow(2, bitsForHosts);
    const networkDecimal = parseInt(networkBinary, 2);
    const newNetworkDecimal = networkDecimal + increment;
    return newNetworkDecimal.toString(2).padStart(32, '0');
}