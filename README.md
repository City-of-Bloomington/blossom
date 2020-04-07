blossom
=======

A skeleton for building web applications and services in PHP.

## Features
Internationalization and Localization ready
All words and language used are already pulled out into .po files.  Continue using the .po files for all language, and you'll be ready to attract translators for your international audience!

Multi-tenant
Host multiple sites using the same codebase.  Each site's data, themes, sessions, and other code implementations are contained in their own data directory.  The path to the data directory can be set in the apache config for each site.  Upgrading the main codebase is simple, with minimal worries about destrorying someone's custom theme.

Themable
Speaking of themes.  All look and feel is provided via drop-in themes.  Any core template or block can be overridden by providing a matching file in your theme directory.


## Install

### Ansible
Ansible scripts are available to assist with setting up a new instance. These also provide a description of requirements, in case you need to install using a different mechanism:

[Install Documentation](ansible/)

### Docker
