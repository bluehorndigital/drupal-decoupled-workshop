#!/bin/bash

lando rebuild -y
lando drush si demo_umami --account-pass=admin --yes
lando drush en workshop_demo --yes

lando drush uli
