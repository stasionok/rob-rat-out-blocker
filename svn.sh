#!/bin/bash

cd svn

# For first push you should add repository
# svn co https://plugins.svn.wordpress.org/wp-drupal-imagecache .

svn add ./assets/*
svn add ./branches/*
svn add ./tags/*
svn add ./trunk/*

if [[ -z $1 ]]; then
    echo -e "\e[31mRun in svn directory \033[4msvn ci -m 'MESSAGE'\033[0m\e[0"
    exit 0;
fi
svn ci -m "${1}"
