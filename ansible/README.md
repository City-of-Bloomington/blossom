Blossom - Ansible
======================

The included ansible playbook and role install the blossom web application along with required dependencies.

These files also serve as living documentation of the system requirements and configurations necessary to run the application.

This assume some familiarity with the Ansible configuration management system and that you have an ansible control machine configured. Detailed instructions for getting up and running on Ansible are maintained as part of our system-playbooks repository:

https://github.com/City-of-Bloomington/system-playbooks

On the ansible control machine, make sure you have everything you need:

    git clone https://github.com/City-of-Bloomington/blossom
    cd blossom/ansible

Dependencies
-------------

Decide how you want to get the other necessary ansible roles:

    ansible-galaxy install --roles-path ./roles -r roles.yml

or for development:

```
git clone https://github.com/City-of-Bloomington/ansible-role-linux.git ./roles/City-of-Bloomington.linux
git clone https://github.com/City-of-Bloomington/ansible-role-apache.git ./roles/City-of-Bloomington.apache
git clone https://github.com/City-of-Bloomington/ansible-role-mysql.git ./roles/City-of-Bloomington.mysql
git clone https://github.com/City-of-Bloomington/ansible-role-php.git ./roles/City-of-Bloomington.php
etc
```

Variables
--------------

Variables are set and configured in a few different places. Update these to match your needs:

  -  group_vars/blossom/public.yml
  -  group_vars/blossom/vault.yml

More information about vaulting passwords is available here:

https://github.com/City-of-Bloomington/system-playbooks/tree/master/group_vars


Run the Playbook
-----------------

    ansible-playbook deploy.yml -i /path/to/inventory --limit=blossom

Additional Information
-------------------------
Did everything work as expected? If not, please let us know:

https://github.com/City-of-Bloomington/blossom/issues

This project and others like it are maintained on the City of Bloomington's Github page:

https://github.com/city-of-bloomington

License
-------

Copyright (c) 2016-2020 City of Bloomington, Indiana

This material is avialable under the GNU Affero General Public License (GLP):
https://www.gnu.org/licenses/agpl-3.0.txt


