#version=RHEL7

# instalacja SL <?php echo $V; ?> netinst
# sieć dhcp
# epel

# System authorization information
auth --enableshadow --passalgo=sha512

# Use network installation
url --url="http://ftp.scientificlinux.org/linux/scientific/<?php echo $V; ?>/x86_64/os"

# Use text mode install
text
# Run the Setup Agent on first boot
firstboot --disable
ignoredisk --only-use=vda

# System language
lang pl_PL.UTF-8
keyboard pl2

# Network information
network --onboot yes --device eth1 --bootproto static --noipv6 --ip=10.243.255.<?php echo $I; ?> --netmask=255.255.255.0 --gateway=10.243.255.1 --nameserver=172.19.243.1
network --onboot yes --device eth0 --bootproto static --noipv6 --ip=172.19.243.<?php echo $I; ?> --netmask=255.255.255.0 --hostname=<?php echo $N; ?>.dro.nask.pl

# Root password
# domyślne - NASK
rootpw --iscrypted $6$bojXVuBc/uCQN775$ZxCvGCUN7H4Im4dJZnnP46mFIMILlM5pl.iCQ2AorZ4hLOaOurDnLPzy/dA4BllEVOJXLk13bBSYNldF.q/Ei1

# Security
selinux --disabled

# Do not configure the X Window System
skipx
# System timezone
timezone Europe/Warsaw --isUtc --ntpservers=2.rhel.pool.ntp.org,3.rhel.pool.ntp.org,0.rhel.pool.ntp.org,1.rhel.pool.ntp.org

# System bootloader configuration
zerombr
bootloader --location=mbr --boot-drive=vda --append="crashkernel=0 rhgb ipv6.disable=1 modprobe.blacklist=sr_mod,cdrom,ppdev,parport_pc,parport,pcspkr,serio_raw"
# Partition clearing information
clearpart --all --initlabel --drives=vda

# vda
part /boot --fstype=ext4 --ondisk=vda --size=512
part pv.01 --fstype=lvmpv --ondisk=vda --grow --size=1024
volgroup vg01 --pesize=4096 pv.01
logvol swap --fstype=swap --name=swap --vgname=vg01 --grow --recommended
logvol / --fstype=ext4 --name=sys --vgname=vg01 --grow --size=1024

%packages
@core
wget
%end

%post
set -x -v
exec 1>/root/postinstall.log 2>&1

echo "" >> /etc/sysconfig/network
echo "NOZEROCONF=yes" >> /etc/sysconfig/network
echo "GATEWAY=10.243.255.1" >> /etc/sysconfig/network

yum remove -y *firmware*
yum remove -y NetworkManager NetworkManager-*
yum remove -y yum-cron dnsmasq firewalld
yum remove -y kernel-tools*
yum remove -y ppp wpa_supplicant

cd /tmp && {
wget http://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm
[[ -f epel-release-latest-7.noarch.rpm ]] && {
rpm -ivh epel-release-latest-7.noarch.rpm
rm -fv epel-release-latest-7.noarch.rpm
yum install -y \
bash-completion \
bind-utils \
iptables \
iptables-services \
htop \
mailx \
mc \
mlocate \
net-tools \
nmap-ncat \
ntp \
ntpdate \
openssh-clients \
sysstat \
redhat-lsb-core \
tcpdump \
telnet \
tmux \
unzip \
vim-enhanced \
yum-utils
}
}

wget -O /tmp/postinstall.tar "http://172.19.243.5/postinstall/postinstall.tar"
cd /tmp && {
tar xvf postinstall.tar
cd /tmp/postinstall && {
cp etc/tmux.conf /etc
cp -r root/.config /root
cp root/.vimrc /root

cat root/.bash_profile.template >> /root/.bash_profile
cat root/.bashrc.template >> /root/.bashrc
}
}

yum clean all
yum makecache fast

%end

