Vagrant.configure('2') do |config|
  config.vm.provider 'lcx'
  config.vm.box = 'fgrehm/centos-6-64-lxc'
  config.vm.hostname = 'employee-app.dev'
  config.vm.synced_folder '.', '/vagrant/employee-app'

  config.vm.provider :lxc do |lxc|
    lxc.customize 'cgroup.memory.limit_in_bytes', '512M'
  end

  config.ssh.username = config.ssh.password = 'vagrant'

  config.hostmanager.enabled = true
  config.hostmanager.manage_host = true

  config.vm.provision 'shell', path: 'data/deploy/provision.sh'
end
