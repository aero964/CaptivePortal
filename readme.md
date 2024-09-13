# 作業メモ

### CaptivePortalを内製化しましょー
###### iptables + PHPで何とか行けんじゃね？
###### CaptivePortalの画面出すWebサーバーと、サーバーそのものをDHCPルーターにする感じで
###### radiusとかめんどうなのは抜き。同意画面出すだけですよん

1. CaptivePortal Web画面用のnginx, PHP(php-fpm)インストール
    - OSはAlma9

2. 特になし

3. NW
   - eth0 WAN 10.7.0.216/24
   - eth1 LAN 192.168.234.250/24
       - ※eth1にはDHCPサーバーをデーモンで動かすことにした `dnf install dhcp-server`

4. IPv4 Forwarding
   - `/etc/sysctl.conf`
       - `net.ipv4.ip_forward = 1`
   - `sysctl -p`

5. iptablesはサービス化した

6. iptablesチェーン
   - 未認証のクライアントの80番、443番、その他のTCPトラフィックをキャプティブポータルへリダイレクト
        - `iptables -t nat -A PREROUTING -i eth1 -p tcp --dport 80 -j DNAT --to-destination 192.168.234.250:80`
        - `iptables -t nat -A PREROUTING -i eth1 -p tcp --dport 443 -j DNAT --to-destination 192.168.234.250:443`

   - chrome対策
        - `iptables -t nat -A PREROUTING -i eth1 -p udp --dport 80 -j DNAT --to-destination 192.168.234.250:80`
        - `iptables -t nat -A PREROUTING -i eth1 -p udp --dport 443 -j DNAT --to-destination 192.168.234.250:443`

   - 80/443以外の全てのTCPトラフィックをリダイレクト
        - `iptables -t nat -A PREROUTING -i eth1 -p tcp -j DNAT --to-destination 192.168.234.250:80`

###### とりあえずここまで...
###### あとはatで時間きたら自動で消したりとか、監視とか、、ですかね
