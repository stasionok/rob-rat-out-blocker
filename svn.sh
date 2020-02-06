#!/bin/bash

cd ./svn/

if [ ! -d \.svn ]; then
  echo -e "\e[31mFor first push you should add repository[0m\e[0"
  echo -e "\e[31m\033[4msvn co https://plugins.svn.wordpress.org/rob-rat-out-blocker ./svn/\033\e[0"
    exit 0;
fi;

svn add ./assets/*
svn add ./branches/*
svn add ./tags/*
svn add ./trunk/*

if [[ -z $1 ]]; then
    echo -e "\e[31mRun in svn directory \033[4msvn ci -m 'MESSAGE'\033[0m\e[0"
    exit 0;
fi
svn ci -m "${1}"
