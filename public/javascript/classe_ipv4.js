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
