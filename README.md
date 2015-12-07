# namecheap-interface
A PHP interface to Namecheap's API

Install via composer.json

```
 "require": {
   "lewnelson/namecheap-interface": "dev-master"
 }
```

This library is intended to allow easy interaction with Namecheaps API. For now you can only interact with your current domains, setting, deleting and getting host/MX records. Setup, configure and edit email forwarding. And configure nameservers either using custom nameservers or setting them to use Namecheaps default nameservers. The library covers the following commands from Namecheaps API and enhances them allowing more of an objective interaction allowing for manipulation of single objects.

I aim to simplify the interaction between your app and Namecheaps API, by formatting responses in sensible ways for further interaction as well as allowing sensibly formatted requests.

## Current commands implemented in various methods

 - domains.getList
 - domains.dns.setDefault
 - domains.dns.setCustom
 - domains.dns.getList
 - domains.dns.getHosts
 - domains.dns.getEmailForwarding
 - domains.dns.setEmailForwarding
 - domains.dns.setHosts

I intend to cover all/majority of commands available and have started with completing the domain commands. This code is still relatively fresh so I would advise against using it in production.

## Documentation

There is no official documentation as of yet, but I will be creating example code which can be used/modified to fit in your project. These will consist of classes in /Examples/Classes which will be split to interact with MethodTypes, or be split further if necessary. These should allow more of an insight as to how the library works and what you are able to achieve with it. The other directories in /Examples are procedural PHP code which correspond to individual actions, including these files directly into your project will run the /Example code from corresponding classes. Feel free to use as much or as little of the /Example code.

Once I have added some more functionality to the library I intend to add some more official documentation as well as the Example code, which will explain in more detail each of the functions available.

## TODO

### domains
 - ~~getList~~
 - getContacts
 - create
 - getTldList
 - setContacts
 - check
 - reactivate
 - renew
 - getRegistrarLock
 - setRegistrarLock
 - getInfo

### domains.dns
 - ~~setDefault~~
 - ~~setCustom~~
 - ~~getList~~
 - ~~getHosts~~
 - ~~getEmailForwarding~~
 - ~~setEmailForwarding~~
 - ~~setHosts~~

### domains.ns
 - create
 - delete
 - getInfo
 - update

### domains.transfer
 - create
 - getStatus
 - updateStatus
 - getList

### ssl
 - activate
 - getInfo
 - parseCSR
 - getApproverEmailList
 - getList
 - create
 - renew
 - resendApproverEmail
 - resendfulfillmentemail
 - reissue
 - purchasemoresans
 - revokecertificate

### users
 - getPricing
 - getBalances
 - changePassword
 - update
 - createaddfundsrequest
 - getAddFundsStatus
 - login
 - resetPassword

### users.address
 - create
 - delete
 - getInfo
 - getList
 - setDefault
 - update

### whoisguard
 - changeemailaddress
 - enable
 - disable
 - unallot
 - discard
 - allot
 - getList
 - renew
