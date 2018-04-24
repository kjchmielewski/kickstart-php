#version=RHEL7

# System authorization information
auth --enableshadow --passalgo=sha512

# Use network installation
url --url="http://ftp.tu-chemnitz.de/pub/linux/centos/7/os/x86_64/"

# Use text mode install
text
# Run the Setup Agent on first boot
firstboot --disable
ignoredisk --only-use=vda

# System language
lang pl_PL.UTF-8
keyboard pl2

# Network information
#network --onboot yes --device eth0 --bootproto static --noipv6 --ip=192.168.123.91 --netmask=255.255.255.0 --hostname=cos7.stumilas.wew
network --onboot yes --device eth1 --bootproto static --noipv6 --ip=10.243.255.<?php echo $IP; ?> --netmask=255.255.255.0 --gateway=10.243.255.1 --nameserver=172.19.243.1
network --onboot yes --device eth0 --bootproto static --noipv6 --ip=172.19.243.<?php echo $IP; ?> --netmask=255.255.255.0 --hostname=<?php echo $HN; ?>.dro.nask.pl

# Root password
# domyślne - NASK
#rootpw --plaintext drut
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
%end

%post
set -x -v
exec 1>/root/postinstall.log 2>&1

useradd instalator
usermod -aG wheel instalator
echo 'instalator:q' | chpasswd

%end
