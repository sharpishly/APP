#!/bin/bash

# Telnet
telnet 192.168.0.22 4222

# Ping
echo "PING" | nc 192.168.0.22 4222

# Nats
nats --version

# Trace
strace nats --version


