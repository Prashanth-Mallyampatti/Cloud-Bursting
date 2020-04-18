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

if(!dojo._hasResource["vcldojo.HoverTooltip"]){dojo._hasResource["vcldojo.HoverTooltip"]=true;dojo.provide("vcldojo.HoverTooltip");dojo.declare("vcldojo._MasterTooltip",dijit._MasterTooltip,{tooltipobj:"",_saveAroundNode:"",templateString:dojo.cache("dijit","templates/Tooltip.html","<div class=\"dijitTooltip dijitTooltipLeft\" id=\"dojoTooltip\">\n\t<div class=\"dijitTooltipContainer dijitTooltipContents\" dojoAttachPoint=\"containerNode\" dojoAttachEvent=\"onmouseenter:_mouseIn,onmouseleave:_mouseOut\" waiRole='alert'></div>\n\t<div class=\"dijitTooltipConnector\" dojoAttachPoint=\"connectorNode\"></div>\n</div>\n"),_mouseIn:function(e){this.tooltipobj._hovering=true;},_mouseOut:function(e){this.tooltipobj._hovering=false;this.hide(this._saveAroundNode);if(this.tooltipobj._showTimer){clearTimeout(this.tooltipobj._showTimer);delete this.tooltip._showTimer;}},show:function(_1,_2,_3,_4,_5){this.tooltipobj=_5;this.tooltipobj._hovering=false;this._saveAroundNode=_2;if(this.aroundNode&&this.aroundNode===_2){return;}if(this.fadeOut.status()=="playing"){this._onDeck=arguments;return;}this.containerNode.innerHTML=_1;var _6=dijit.placeOnScreenAroundElement(this.domNode,_2,dijit.getPopupAroundAlignment((_3&&_3.length)?_3:dijit.Tooltip.defaultPosition,!_4),dojo.hitch(this,"orient"));dojo.style(this.domNode,"opacity",0);this.fadeIn.play();this.isShowingNow=true;this.aroundNode=_2;}});dijit.showTooltip=function(_7,_8,_9,_a,_b){if(!dijit._masterTT){dijit._masterTT=new vcldojo._MasterTooltip();}return dijit._masterTT.show(_7,_8,_9,_a,_b);};dijit.hideTooltip=function(_c){if(!dijit._masterTT){dijit._masterTT=new vcldojo._MasterTooltip();}return dijit._masterTT.hide(_c);};dojo.declare("vcldojo.HoverTooltip",dijit.Tooltip,{_hovering:false,_onUnHover:function(e){if(this._focus){return;}if(this._showTimer){clearTimeout(this._showTimer);delete this._showTimer;}if(!this._hideTimer){this._hideTimer=setTimeout(dojo.hitch(this,function(){this.close();}),500);}},open:function(_d){if(this._showTimer){clearTimeout(this._showTimer);delete this._showTimer;}dijit.showTooltip(this.label||this.domNode.innerHTML,_d,this.position,!this.isLeftToRight(),this);this._connectNode=_d;this.onShow(_d,this.position);},close:function(){if(this._hovering==true){return;}if(this._connectNode){dijit.hideTooltip(this._connectNode);delete this._connectNode;this.onHide();}if(this._showTimer){clearTimeout(this._showTimer);delete this._showTimer;}}});}