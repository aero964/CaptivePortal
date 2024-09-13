# 作業メモ

1. CaptivePortal用nginx, PHPインストール

2. NW
   - eth0 WAN 10.7.0.216/24
   - eth1 LAN 192.268.234.250/24
       - ※eth1にはDHCPを設定した

3. IPv4 Forwarding
   - `/etc/sysctl.conf`
       - `net.ipv4.ip_forward = 1`
   - `sysctl -p`

4. iptablesはサービス化した

5. iptablesチェーン
   - 未認証のクライアントの80番、443番、その他のTCPトラフィックをキャプティブポータルへリダイレクト
        - `iptables -t nat -A PREROUTING -i eth1 -p tcp --dport 80 -j DNAT --to-destination 192.168.234.250:80`
        - `iptables -t nat -A PREROUTING -i eth1 -p tcp --dport 443 -j DNAT --to-destination 192.168.234.250:443`

   - chrome対策
        - `iptables -t nat -A PREROUTING -i eth1 -p udp --dport 80 -j DNAT --to-destination 192.168.234.250:80`
        - `iptables -t nat -A PREROUTING -i eth1 -p udp --dport 443 -j DNAT --to-destination 192.168.234.250:443`

   - 80/443以外の全てのTCPトラフィックをリダイレクト
        - `iptables -t nat -A PREROUTING -i eth1 -p tcp -j DNAT --to-destination 192.168.234.250:80`

###### とりあえずここまで...
