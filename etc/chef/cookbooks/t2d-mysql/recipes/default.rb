#
# Cookbook Name:: t2d-mysql
# Recipe:: default
#
# Copyright (C) 2017 Eugene Kim
#
# All rights reserved - Do Not Redistribute
#

include_recipe 'yum-mysql-community::mysql57'

package ['mysql-community-devel', 'mysql-server']

execute 'mysql_init' do
  command '/usr/sbin/mysqld --initialize-insecure --user=mysql'
end

