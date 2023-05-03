Ansible
======================
This playbook installs the blossom web application.

This assumes some familiarity with the Ansible configuration management system and that you have an ansible control machine configured.  Before running the playbook, you must first have a tarball of the application that was previously built.  There is a Makefile in the project root.

Dependencies
-------------
This playbook requires a few roles.
You can probably use ansible-galaxy to download them:

    ansible-galaxy install --roles-path ./roles -r roles.yml

or for development:

```
git clone https://github.com/City-of-Bloomington/ansible-role-linux.git  ./roles/City-of-Bloomington.linux
git clone https://github.com/City-of-Bloomington/ansible-role-apache.git ./roles/City-of-Bloomington.apache
git clone https://github.com/City-of-Bloomington/ansible-role-mysql.git  ./roles/City-of-Bloomington.mysql
git clone https://github.com/City-of-Bloomington/ansible-role-php.git    ./roles/City-of-Bloomington.php
etc
```

Variables
--------------
You'll need to set these variables somewhere in your inventory.

```yml
blossom_archive_path: "../build/blossom.tar.gz"
blossom_install_path: "/srv/sites/blossom"
blossom_site_home:    "/srv/data/blossom"

blossom_base_uri: "/blossom"
blossom_base_url: "https://{{ ansible_host }}{{ blossom_base_uri }}"
```

Run the Playbook
-----------------

    ansible-playbook deploy.yml -i /path/to/inventory --limit=host.example.com

License
-------

Copyright (c) 2016-2023 City of Bloomington, Indiana

This material is avialable under the GNU Affero General Public License (GLP):
https://www.gnu.org/licenses/agpl-3.0.txt


