# debian 8/9 LVM, only /root and swap 10GB HDD
# localization
d-i debian-installer/locale string pl_PL.UTF-8
d-i keyboard-configuration/xkb-keymap select pl
# network configuration
d-i netcfg/choose_interface select auto
d-i netcfg/hostname string <?php echo $HN; ?>.dro.nask.pl

# mirror settings
d-i mirror/protocol select http
d-i mirror/country string PL
d-i mirror/http/mirror select ftp.de.debian.org
d-i mirror/https/directory string /debian/
# account setup
d-i passwd/make-user boolean false
d-i passwd/root-password-crypted password $6$h6U2an.h$/Gq8jn9AiGuoqEPcWfrNvJC26S5jk.Nea4WB9Xjf8o2tprnXxHF.ZCSj6PAXjR9.0HIHxMJSKe06souiKoTgu0
# tz setup
d-i time/zone select Europe/Warsaw
d-i clock-setup/ntp-server string 0.debian.pool.ntp.org
d-i clock-setup/utc boolean true
#
# partitionig

d-i partman-lvm/device_remove_lvm boolean true
d-i partman-md/device_remove_md boolean true
d-i partman-lvm/confirm boolean true
d-i partman-lvm/confirm_nooverwrite boolean true
d-i partman-auto-lvm/guided_size string max

d-i partman-auto/disk string /dev/vda
d-i partman-auto/method string lvm
d-i partman-lvm/device_remove_lvm boolean true
d-i partman-lvm/device_remove_lvm_span boolean true
d-i partman-auto/purge_lvm_from_device  boolean true
d-i partman-auto-lvm/new_vg_name string system

d-i partman-auto/choose_recipe select custompartitioning

d-i partman-auto/expert_recipe string                         \
      boot-root ::                                            \
              300 300 300 ext4                                \
                      $primary{ }                             \
                      $bootable{ }                            \
                      method{ format } format{ }              \
                      use_filesystem{ } filesystem{ ext4 }    \
                      mountpoint{ /boot }                     \
              .                                               \
              1024 1 100% ext4                                \
                      $primary{ }                             \
                      method{ lvm }                           \
                      device{ /dev/vda2 }                     \
                      vg_name{ system }                       \
              .                                               \
              6000 7500 7000 ext4                             \
                      $lvmok{ } in_vg{ system }               \
                      method{ format } format{ }              \
                      use_filesystem{ } filesystem{ ext4 }    \
                      mountpoint{ / }                         \
              .                                               \
              1536 1536 1536 linux-swap                       \
                      $lvmok{ } in_vg{ system }               \
                      method{ swap } format{ }                \
              .
 
d-i partman-partitioning/confirm_write_new_label boolean true
d-i partman/choose_partition select finish
d-i partman/confirm boolean true
d-i partman/confirm_nooverwrite boolean true

# base system
d-i  base-installer/kernel/image select linux-image-amd64

# apt-setup
d-i apt-setup/non-free boolean true
d-i apt-setup/contrib boolean true

# package selection
tasksel tasksel/first multiselect standard
# d-i pkgsel/upgrade select none
d-i pkgsel/include string apt-file dnsutils htop lsb-release mc mlocate net-tools ntp ntpdate openssh-server parted pv resolvconf sudo tcpdump tmux vim wajig

# bootloader
d-i grub-installer/only_debian boolean true
d-i grub-installer/choose_bootdev	select	/dev/vda

# postinstall
d-i preseed/late_command string in-target wget -O /tmp/postinstall.sh "http://<?php echo $IP; ?>/postinstall.sh"; in-target wget -O /tmp/postinstall.tar "http://<?php echo $IP; ?>/postinstall.tar"; in-target chmod +x /tmp/postinstall.sh; in-target /tmp/postinstall.sh;
# d-i preseed/late_command string in-target wget -O /tmp/postinstall.sh "http://172.19.243.5/deb/postinstall.sh"; in-target wget -O /tmp/postinstall.tar "http://172.19.243.5/postinstall/postinstall.tar"; in-target chmod +x /tmp/postinstall.sh; in-target /tmp/postinstall.sh;

# finishing instalation
d-i debian-installer/exit/poweroff boolean true
