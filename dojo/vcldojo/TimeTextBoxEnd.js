/*
Licensed to the Apache Software Foundation (ASF) under one or more
contributor license agreements.  See the NOTICE file distributed with
this work for additional information regarding copyright ownership.
The ASF licenses this file to You under the Apache License, Version 2.0
(the "License"); you may not use this file except in compliance with
the License.  You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/

if(!dojo._hasResource["vcldojo.TimeTextBoxEnd"]){dojo._hasResource["vcldojo.TimeTextBoxEnd"]=true;dojo.provide("vcldojo.TimeTextBoxEnd");dojo.require("dijit.form.TimeTextBox");dojo.declare("vcldojo.TimeTextBoxEnd",[dijit.form.TimeTextBox],{startid:"",invalidMessage:"(initial message)",isValid:function(_1){if(dijit.byId(this.startid)){var _2=dijit.byId(this.startid).value;if(_2!==null&&this.value!==null&&_2>=this.value){return false;}}return this.inherited(arguments);},postCreate:function(){if(usenls&&"This must be a valid time that is greater than the start time" in nlsmessages){this.invalidMessage=nlsmessages["This must be a valid time that is greater than the start time"];}else{this.invalidMessage="This must be a valid time that is greater than the start time";}this.inherited(arguments);}});}