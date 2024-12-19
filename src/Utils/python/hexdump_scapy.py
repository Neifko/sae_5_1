import argparse
import scapy.all as scapy
from scapy.utils import hexdump
import json
import sys
import os

hexdump_dir = os.path.join(os.getcwd(), '..', '..', 'Views', 'data', 'hexdumpFiles')

def output_json(data, name):
    """
    Print data in JSON format.
    """
    with open(os.path.join(hexdump_dir, f"{name}.json"), 'w') as hexdump_data_file:
        json.dump(data, hexdump_data_file, indent=len(data))

def list_interfaces():
    """
    List all available network interfaces.
    """
    interfaces = scapy.get_if_list()
    output_json({"interfaces": interfaces}, "interfaces")

def capture_packet(interface):
    """
    Capture a single packet on the specified interface.
    """
    try:
        packet = scapy.sniff(count=1, iface=interface)[0]
        result = {
            "status": "success",
            "packet_summary": packet.show(dump=True),
            "hexdump": hexdump(packet, dump=True)
        }
        output_json(result, "capture")
    except Exception as e:
        output_json({"status": "error", "message": str(e)}, "capture")

def analyze_data(raw_data):
    """
    Analyze raw data and print it in hexdump format.
    """
    try:
        decoded_data = bytes.fromhex(raw_data)
        result = {
            "status": "success",
            "hexdump": hexdump(decoded_data, dump=True)
        }
        output_json(result, "simple_analyze")
    except ValueError:
        output_json({"status": "error", "message": "Invalid raw data. Please provide hexadecimal input."}, "simple_analyze")

def create_sample_packet(dst_ip):
    """
    Create a sample packet with the given destination IP.
    """
    try:
        packet = scapy.IP(dst=dst_ip) / scapy.ICMP()
        result = {
            "status": "success",
            "packet_summary": packet.show(dump=True),
            "hexdump": hexdump(packet, dump=True)
        }
        output_json(result, "packet")
    except Exception as e:
        output_json({"status": "error", "message": str(e)}, "packet")

def compare_data(data1, data2):
    """
    Compare two sets of data and print differences in hexdump format.
    """
    try:
        decoded_data1 = bytes.fromhex(data1)
        decoded_data2 = bytes.fromhex(data2)
        
        result = {
            "status": "success",
            "data1_hexdump": hexdump(decoded_data1, dump=True),
            "data2_hexdump": hexdump(decoded_data2, dump=True),
            "are_identical": decoded_data1 == decoded_data2
        }
        output_json(result, "double_analyze")
    except ValueError:
        output_json({"status": "error", "message": "Invalid data provided. Please ensure both inputs are hexadecimal."}, "double_analyze")

def main():
    parser = argparse.ArgumentParser(description="Hexdump Service Tool")
    parser.add_argument("action", type=str, choices=["capture", "analyze", "sample", "compare", "list_interfaces"],
                        help="Action to perform")
    parser.add_argument("--interface", type=str, help="Network interface for capturing packets")
    parser.add_argument("--data", type=str, help="Raw data to analyze in hexadecimal format")
    parser.add_argument("--dst_ip", type=str, help="Destination IP for the sample packet")
    parser.add_argument("--data1", type=str, help="First set of data for comparison (hexadecimal)")
    parser.add_argument("--data2", type=str, help="Second set of data for comparison (hexadecimal)")

    args = parser.parse_args()

    if args.action == "list_interfaces":
        list_interfaces()
    elif args.action == "capture":
        if not args.interface:
            output_json({"status": "error", "message": "--interface is required for capturing packets."})
            sys.exit(1)
        capture_packet(args.interface)
    elif args.action == "analyze":
        if not args.data:
            output_json({"status": "error", "message": "--data is required for analyzing raw data."})
            sys.exit(1)
        analyze_data(args.data)
    elif args.action == "sample":
        if not args.dst_ip:
            output_json({"status": "error", "message": "--dst_ip is required for creating a sample packet."})
            sys.exit(1)
        create_sample_packet(args.dst_ip)
    elif args.action == "compare":
        if not args.data1 or not args.data2:
            output_json({"status": "error", "message": "--data1 and --data2 are required for comparing data."})
            sys.exit(1)
        compare_data(args.data1, args.data2)

if __name__ == "__main__":
    main()
