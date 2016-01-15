V2.0.0 (2016-01-15)
-------------------

Features:
* Add `LoggerBus`
* Add `EventDispatcherBus`
* Add `$handleType` inside the events

Changes:
* Command Dispatched log message move from `info` to `notice`

BC breaks:
* `RedisBus` and `DirectBus` constructor signature
* Remove `DirectCommandBusInterface`
* Add `CommandBusInterface::getHandleType`
* Remove injected `$bus` from `PreHandleCommandEvent`
 
V1.1.2 (2015-12-11)
-------------------

- Support Symfony3

V1.1.1 (2015-10-14)
-------------------

- We have to dispatch event before serialize commands. (253ae438de2341d175375434a0ac3b758347c701)

V1.1.0 (2015-10-14)
-------------------

- Add onDirectResponse (f9b75f)
