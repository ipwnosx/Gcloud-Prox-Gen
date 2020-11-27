## GoogleCloud Proxy Gen

Gcloud Prox Gen, ie; for Datacenter, Residential Proxies (mostly used with Sneaker Bots)

#GoogleCloud Proxy Gen

=============================================================
INSTALLATION
=============================================================

Login to Ubuntu 16.04 server as user "root", then run following commands

apt update
apt upgrade -y
apt install -y git php php-zip php-curl php-ssh2

Edit File

nano ~/.bashrc

Add following to end

alias google-create='/usr/bin/php /root/gcloud/create.php'
alias google-list='/usr/bin/php /root/gcloud/list.php'
alias google-delete='/usr/bin/php /root/gcloud/delete.php'

Now you need to reboot the server with command

"reboot"

=============================================================
Install Google Cloud SDK
=============================================================

Run following commands

export CLOUD_SDK_REPO="cloud-sdk-$(lsb_release -c -s)"
echo "deb http://packages.cloud.google.com/apt $CLOUD_SDK_REPO main" > /etc/apt/sources.list.d/google-cloud-sdk.list
curl https://packages.cloud.google.com/apt/doc/apt-key.gpg | apt-key add -
apt-get update
apt-get install google-cloud-sdk -y
gcloud init


"gcloud init" will ask you to login to google account, then select cloud project you want.

=============================================================
SETTING CRONJOB
=============================================================

In server, set following cronjob. Command to add cronjob is "crontab -e", then select editor, paste following

* * * * * /usr/bin/php  /root/gcloud/delete-cron.php 1> /dev/null 2> /dev/null

=============================================================
Using the Script
=============================================================

google-create
google-list
google-delete

=============================================================
Authentication Options
=============================================================


Default Configuration is: define('AUTH_TYPE','IP'); # IP, LOGIN

if you want to change the authentication from IP authentication to user:pass authentication, 
you must edit this to the following:

define('AUTH_TYPE','LOGIN'); # IP, LOGIN





