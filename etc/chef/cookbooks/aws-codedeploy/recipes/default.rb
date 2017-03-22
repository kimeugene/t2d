#
# Cookbook Name:: aws-codedeploy
# Recipe:: default
#
# Copyright (C) 2016 YOUR_NAME
#
# All rights reserved - Do Not Redistribute
#


package 'ruby'                  # required for install script

remote_file '/tmp/install' do
  source 'https://aws-codedeploy-us-east-1.s3.amazonaws.com/latest/install'
  owner 'root'
  group 'root'
  mode '0755'
end

execute 'install_codedeploy_agent' do
  command '/tmp/install auto && touch /tmp/.codedeploy'
  not_if '[ -f /tmp/.codedeploy ]'
end

service "codedeploy-agent" do
    action [ :enable, :start ]
end

