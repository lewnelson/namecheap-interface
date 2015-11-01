# namecheap-interface
A PHP interface to Namecheap's API

Install via composer.json

```
 "require": {
   "lewnelson/namecheap-interface": "@dev"
 }
```

This library is intended to allow easy interaction with Namecheaps API. For now you can only interact with your current domains, setting, deleting and getting host/MX records. Setup, configure and edit email forwarding. And configure nameservers either using custom nameservers or setting them to use Namecheaps default nameservers. The library covers the following commands from Namecheaps API and enhances them allowing more of an objective interaction allowing for manipulation of single objects.

## Current commands implemented in various methods

 - namecheap.domains.getList
 - namecheap.domains.dns.setDefault
 - namecheap.domains.dns.setCustom
 - namecheap.domains.dns.getList
 - namecheap.domains.dns.getHosts
 - namecheap.domains.dns.getEmailForwarding
 - namecheap.domains.dns.setEmailForwarding
 - namecheap.domains.dns.setHosts

I intend to cover all/majority of commands available and have started with completing the domain commands. This code is still relatively fresh so I would advise against using it in production.

## Documentation

There is no official documentation as of yet, but I will create files in the Examples directory which will bring more of an insight on how to use the library. If something is missing from there for now try to follow the code until I can implement some proper documentation.

I plan on adding all of the remaining commands, but will leave the commands which are going to require billing till last.
