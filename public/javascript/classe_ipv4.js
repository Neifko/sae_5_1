function validateIP(ip) {
    const regex = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?).(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?).(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?).(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
    return regex.test(ip);
}

function getIPv4ClassAndMask(ipv4) {
    const firstOctet = Number(ipv4.split('.')[0]);

    if (firstOctet >= 1 && firstOctet <= 126) {
        return { class: 'A', mask: '255.0.0.0', cidr: '/8' };
    } else if (firstOctet >= 128 && firstOctet <= 191) {
        return { class: 'B', mask: '255.255.0.0', cidr: '/16' };
    } else if (firstOctet >= 192 && firstOctet <= 223) {
        return { class: 'C', mask: '255.255.255.0', cidr: '/24' };
    } else if (firstOctet >= 224 && firstOctet <= 239) {
        return { class: 'D (Multicast)', mask: 'N/A', cidr: 'N/A' };
    } else if (firstOctet >= 240 && firstOctet <= 255) {
        return { class: 'E (Expérimental)', mask: 'N/A', cidr: 'N/A' };
    } else {
        return null;
    }
}

document.getElementById('ipv4-form').addEventListener('submit', function (event) {
    event.preventDefault(); // recharge pas la page

    const ipv4Address = document.getElementById('ipv4_address').value;
    const resultDiv = document.getElementById('result');

    if (validateIP(ipv4Address)) {
        const result = getIPv4ClassAndMask(ipv4Address);

        if (result) {
            resultDiv.textContent = `Adresse IPv4 : ${ipv4Address}\nClasse : ${result.class}\nMasque : ${result.mask}\nNotation CIDR : ${result.cidr}`;
            resultDiv.className = 'success';
        } else {
            resultDiv.textContent = 'Erreur : Adresse IPv4 hors plage valide.';
            resultDiv.className = 'error';
        }
    } else {
        resultDiv.textContent = 'Erreur : Adresse IPv4 invalide. Assurez-vous de la bonne syntaxe du IPv4.';
        resultDiv.className = 'error';
    }

    resultDiv.style.display = 'block';
});
