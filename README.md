# misp-webservice-client
A MISP Webservice client for connecting 2 different versions of MISP and sharing events.

Brief:
This readme details the use of the web service client to connect one MISP with another to automatically share event data between them while removing the dependency on the versions being connected. There are 2 connectors 1 for each direction as the concerns need to be managed differently. All other functionality such as tagging is provided by the Rest API.

Separating concerns between versions
The following is a detailed breakdown of all the values that need changing to create a valid event between versions.
 
Distribution level 
It is advised to set this to [distribution] => 0
Available version values
2.3 = 0-3
2.4 = 0-4

Org
2.4 Org
In 2.4 this is an Array and the name must be moved 
          [Org] => Array
                (
                    [0] => Array
                        (
                            [id] => 1
                            [name] => ADMIN
                            [uuid] => 5638f250-41e8-478a-9329-05430a00020f
                        )

                )


2.3 Org
Move Array [name] value 
[org] => ADMIN

Sharing group
2.4 with no group
[sharing_group_id] => 0

2.4 with group
       [SharingGroup] => Array
                (
                    [0] => Array
                        (
                            [id] => 1
                            [name] => test group
                            [releasability] => m3
                            [description] => this is an m3 test
                            [uuid] => 5657447b-3e54-430f-aebc-0486c0a8fa0c
                            [organisation_uuid] => 5638f250-41e8-478a-9329-05430a00020f
                            [org_id] => 1
                            [active] => 1
                            [created] => 2015-11-26 18:41:50
                            [modified] => 2015-11-26 18:42:19
                            [local] => 1
                            [SharingGroupOrg] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 1
                                            [sharing_group_id] => 1
                                            [org_id] => 1
                                            [extend] => 1
                                            [Organisation] => Array
                                                (
                                                    [0] => Array
                                                        (
                                                            [id] => 1
                                                            [name] => ADMIN
                                                            [uuid] => 5638f250-41e8-478a-9329-05430a00020f
                                                        )

                                                )

                                        )

                                )

                            [SharingGroupServer] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 1
                                            [sharing_group_id] => 1
                                            [server_id] => 0
                                            [all_orgs] => 
                                            [Server] => Array
                                                (
                                                    [0] => Array
                                                        (
                                                        )

                                                )

                                        )

                                )

                        )

                )

2.3 sharing group
does not exist and must be unset

Attributes
2.4 Attributes
[Attribute] => Array
                (
                    [0] => Array
                        (
                            [id] => 4274
                            [type] => comment
                            [category] => Internal reference
                            [to_ids] => 
                            [uuid] => 56574000-6cb0-4635-a4c2-0489c0a8fa0c
                            [event_id] => 22
                            [distribution] => 3
                            [timestamp] => 1448559797
                            [comment] => 
                            [sharing_group_id] => 0
                            [value] => test comment
                            [SharingGroup] => Array
                                (
                                )

                            [ShadowAttribute] => Array
                                (
                                )

                        )

                )


2.3 Attributes
[Attribute] => Array
                (
                    [0] => Array
                        (
                            [id] => 6352
                            [type] => comment
                            [category] => Internal reference
                            [to_ids] => 
                            [uuid] => 56573fc3-9d8c-432b-923b-0495c0a8fa0a
                            [event_id] => 34
                            [distribution] => 3
                            [timestamp] => 1448558531
                            [comment] => 
                            [value] => test values
                            [ShadowAttribute] => Array
                                (
                                )

                        )

                )

Unset 2.4 Attribute  [sharing_group_id] => 0

2.4 Attribute Distribution
   [distribution] => 3

Other features
Checking if event exists already and needs updating by asking the API using POST and see what it returns.
(PUT) update

Logging
Running the script will log all event UUID's to a file with time and date
You can also store the event json that was trasferred for later use.


