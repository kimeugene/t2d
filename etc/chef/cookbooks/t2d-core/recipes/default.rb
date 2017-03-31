#
# Cookbook Name:: t2d-core
# Recipe:: default
#
# Copyright (C) 2017 YOUR_NAME
#
# All rights reserved - Do Not Redistribute
#


include_recipe 'yum-remi-chef::remi-php71'
include_recipe 'nginx::default'

user 'www-data' do
  home '/home/www-data'
  shell '/bin/bash'
end


directory "/var/www/logs" do
  owner 'www-data'
  group 'www-data'
  mode '0755'
  action :create
end


package ['git', 'net-tools', 'emacs'] do
  action :install
end

package ['php71-php-cli', 'php71-php-fpm', 'php71-php-mysqlnd', 'php71-php-mbstring', 'php71-php-xml', 'php71-php-pecl-zip'] do
  action :install
end

template "/etc/opt/remi/php71/php-fpm.d/www.conf" do
  source "www.conf.erb"
  mode 00644
  owner "root"
  group "root"
end

template "/etc/opt/remi/php71/php.ini" do
  source "php.ini.erb"
  mode 00644
  owner "root"
  group "root"
end

#execute 'update_lib' do
#  command 'ln /usr/lib64/libsasl2.so.3 /usr/lib64/libsasl2.so.2'
#  not_if { File.exists?("/usr/lib64/libsasl2.so.2") }
#end

service "php71-php-fpm" do
  supports :start => true, :stop => true, :restart => true
  action [ :enable, :start ]
end

execute 'create_symlink' do
  command 'ln /usr/bin/php71 /usr/bin/php'
  not_if { File.exists?("/usr/bin/php") }
end
