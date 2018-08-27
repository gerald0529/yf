(function($) {
	$.fn.combo = function(opts) {
		if(this.length == 0) {
			return this;
		}
		var returnValue, args = arguments;
		this.each(function() {
			var instance = $(this).data('_combo');
			//如果第一个参数是String，则调用相应方法,其他参数作为方法的参数，但必须先生成实例
			if(typeof(opts) == 'string'){
				if(!instance){return ;}//实例未生成
				if(typeof(instance[opts]) === 'function'){
					args = Array.prototype.slice.call(args, 1 );
					returnValue = instance[opts].apply(instance, args);
				}
			}
			//如果参数是配置对象，则生成实例
			else {
				if(!instance){
					instance = new $.Combo($(this),opts);
					$(this).data('_combo', instance);
				}
			}

		});
		//有返回值则返回返回值，无返回值返回调用的jQuery对象，保持jQuery链式调用
		return returnValue === undefined ? this : returnValue;
	}

	$.fn.getCombo = function(){
		return $.Combo.getCombo(this);
	}

 
	/**
		CLASS : Combo
	*/
	$.Combo = function(obj, opts) {
		this.obj = obj;
		this.opts = $.extend(true, {}, $.Combo.defaults, opts);
		this.dataOpt = this.opts.data;
		this._selectedIndex = -1;
		this.addQuery = true;
		this._disabled = typeof(this.opts.disabled) != 'undefined' ? !!this.opts.disabled : !!this.obj.attr('disabled');
		
		//将回调直接作为实例的属性来引用，方便需要根据条件更改回调的情况
		$.extend(this, this.opts.callback);

		this._init();
	}

	/**
		*类方法，获取combo实例
	*/
	$.Combo.getCombo = function(obj){
		obj = $(obj);
		if(obj.length == 0) {
			return ;
		} else if (obj.length == 1){
			return obj.data('_combo');
		} else if ( obj.length > 1) {
			var objArray = [];
			obj.each(function(idx){
				objArray.push($(this).data('_combo'));
			})
			return objArray;
		}
	}

	$.Combo.prototype = {

		constructor: $.Combo,

		/**
			*初始化Combo
		*/
		_init: function() {
			var opts = this.opts;
			if (this.obj[0].tagName.toLowerCase() == 'select') {
				this.originSelect = this.obj;
				this.dataOpt = this._getDataFromSelect();
			};

			this._createCombo();
			this.loadData(this.dataOpt, opts.defaultSelected, opts.defaultFlag)

			this._handleDisabled(this._disabled);
			this._bindEvent();
		},


		loadData: function(data, selected, flag){
			if (this.xhr) {
				this.xhr.abort();
			}
			this.empty(false);
			this.dataOpt = data;
			this.mode = this._getRenderMode();
			if(!this.mode){return ;}
			if(this.mode == 'local'){
				//this.rawData = data;
				this._formatData();
				this._populateList(this.formattedData);
				this._setDefaultSelected(selected, flag);
			} else if(this.mode == 'remote'){
				this._loadAjaxData(selected, flag);
			}
		},
		
		activate: function() {
			if (!this.focus) {
				this.input.focus();
			};
			this.wrap.addClass(this.opts.activeCls);
			this.active = true;	
		},

		_blur: function(){
			if (!this.active) {
				return ;
			}
			this.collapse();
			if(this.opts.editable && this.opts.forceSelection){
				this.selectByText(this.input.val());
				if (this._selectedIndex == -1) {this.input.val('')};
			}
			this.wrap.removeClass(this.opts.activeCls);
			this.active = false;
			if(typeof this.onBlur == 'function'){
				this.onBlur();
			}
		},

		
		blur: function() {
			if (this.focus) {
				this.input.blur();
			}
			this._blur();
		},
	

		/**
			*绑定事件
		*/
		_bindEvent: function() {
			var self = this, opts = this.opts, itemSelector = '.' + opts.listItemCls;
			self.list.on('click', itemSelector, function(e){
				if(!$(this).hasClass(opts.selectedCls)){
					self.selectByItem($(this));
				}
				self.collapse();
				self.input.focus();
				if (typeof opts.callback.onListClick == 'function') {
					opts.callback.onListClick.call(self);
				};
			}).on('mouseover', itemSelector, function(e){
				$(this).addClass(opts.hoverCls).siblings().removeClass(opts.hoverCls);
			}).on('mouseleave', itemSelector, function(e){
				$(this).removeClass(opts.hoverCls);
			});

			self.input.on('focus', function(e){
				self.wrap.addClass(opts.activeCls);
				self.focus = true;
				self.active = true;
			}).on('blur', function(e){
				self.focus = false;
			});
			
			if(!opts.editable){
				self.input.on('click', function(e){
					self._onTriggerClick();
				});
			} else {
				self.input.on('click', function(e){
					//this.select();
				});
			}
			
			if(self.trigger){
				self.trigger.on('click', function(e){
					self._onTriggerClick();
				});
			}

			$(document).on('click', function(e){
				var target  = e.target || e.srcElement;
				if( $(target).closest(self.wrap).length == 0 && $(target).closest(self.listWrap).length == 0 ){
					self.blur();
					//需要判断是否在激活状态，不然每次点击document都会出发blur
				}
			});

			this.listWrap.on('click', function(e){
				e.stopPropagation();
				//self.wrap.trigger('click');
			});

			$(window).on('resize', function(){
				self._setListPosition();
			});

			this._bindKeyEvent();
		},

		_bindKeyEvent: function(){
			var self = this, opts = this.opts;
			var KEY = {
				backSpace: 8,
				esc: 27,
				f7: 118,
				up: 38,
				down: 40,
				tab: 9,
				enter: 13,
				home: 36,
				end: 35,
				pageUp: 33,
				pageDown: 34,
				space: 32
			}
			var isCharKey;

			this.input.on('keydown', function(e){
				switch (e.keyCode) {
					case KEY.tab:
						self._blur();
						break ;

					case KEY.down:
					case KEY.up:
						if(!self.isExpanded){
							self._onTriggerClick();
						} else {
							var direction = e.keyCode == KEY.down ? 'next' : 'prev';
							self._setItemFocus(direction);
						}
						e.preventDefault();
						break ;
					
					case KEY.enter:
						if(self.isExpanded && !self.doingQuery){
							var item = self.list.find('.' + opts.hoverCls);
							if(item.length > 0){
								self.selectByItem(item);
							}
							self.collapse();
						}
						if(typeof opts.callback.onEnter === 'function'){
							opts.callback.onEnter(e);
						}
						break ;

					case KEY.home:
					case KEY.end:
						if(self.isExpanded){
							var item =  e.keyCode == KEY.home ? self.list.find('.' + opts.listItemCls).eq(0) : self.list.find('.' + opts.listItemCls).filter(':last');
							self._scrollToItem(item);
							e.preventDefault();
						}
						break ;
					
					case KEY.pageUp:
					case KEY.pageDown:
						if(self.isExpanded){
							var direction = e.keyCode == KEY.pageUp ? 'up' : 'down';
							self._scrollPage(direction);
							e.preventDefault();
						}
						break ;
				}
			}).on('keyup',function(e){
				if(!opts.editable){return ;}
				var k = e.which;
			    //是否功能键
			    //112-123 F键   16-20 16shift 17ctrl 18alt 19pause 20capsLock
			    // 8backspace 9tab 13enter 27esc     
			    //33-40 33home 34pageup 35end 36pagedown 37up 38left 39down 40right
			    //44-46 44insert 45delete 46print
			    //144 145 144numlock 145 scorllLock
			    var isFuncKey = k == 8 || k == 9 || k == 13 || k == 27 || (k >= 16 && k <= 20) ||(k >= 33 && k <= 40) || 
			    				(k >= 44 && k <= 46) || (k >= 112 && k <= 123) || k == 144 || k == 145;
				var q = self.input.val();
				if (!isFuncKey || k == KEY.backSpace) {
					self.doDelayQuery(q);
				};
			});

			$(document).on('keydown', function(e){
				if(e.keyCode == KEY.esc){
					self.collapse();
				}
			});
			
		},




		distory: function() {
		},


		enable: function() {
			this._handleDisabled(false);
		},

		disable: function(disabled) {
			disabled = typeof disabled == 'undefined' ? true : !!disabled; 
			this._handleDisabled(disabled);
		},

		_handleDisabled: function(disabled){
			var opts = this.opts;
			this._disabled = disabled;
			disabled == true ? this.wrap.addClass(opts.disabledCls) : this.wrap.removeClass(opts.disabledCls);
			this.input.attr('disabled', disabled);
		},
		

		/**
			* 生成ComboBox
		*/
		_createCombo: function() {
			var opts = this.opts, w = parseInt(this.opts.width), wrap, input, trigger, appendFlag;
			if(this.originSelect){
				this.originSelect.hide();
			}

			if(this.obj[0].tagName.toLowerCase() == 'input'){
				this.input = this.obj;
			} else{
				input = this.obj.find('.' + opts.inputCls);
				this.input = input.length > 0 ? input : $('<input type="text" class="'+ opts.inputCls +'"/>');
			}
			this.input.attr({
				autocomplete: 'off',
				readOnly: !opts.editable
			}).css({
				'cursor' : !opts.editable ? 'default' : ''
			});
			
			
			trigger = $(this.obj).find('.' + opts.triggerCls);
			if(trigger.length > 0){
				this.trigger = trigger;
			} else if(opts.trigger !== false){
				this.trigger = $('<span class="' + opts.triggerCls + '"></span>')
			}


			if(this.obj.hasClass(opts.wrapCls)){
				wrap = this.obj;
			} else {
				wrap = this.obj.find('.' + opts.wrapCls);
			}
			if(wrap.length > 0){
				this.wrap = wrap.append(this.input, this.trigger);
			} else if(this.trigger){
				this.wrap = $('<span class="' + opts.wrapCls + '"></span>').append(this.input, this.trigger);
				if((this.originSelect && this.obj[0] == this.originSelect[0] ) || this.obj[0] == this.input[0]){
					if(this.obj.next().length > 0){
						this.wrap.insertBefore(this.obj.next());
					} else {
						this.wrap.appendTo(this.obj.parent());
					}
				} else {
					this.wrap.appendTo(this.obj);
				}
			}
			if(this.wrap && opts.id){
				this.wrap.attr('id', opts.id);
			}
			if(!this.wrap){
				this.wrap = this.input;
			}

			this._setComboLayout(w);

			//下拉菜单
			
			this.list = $('<div />').addClass(opts.listCls).css({position: 'relative', overflow: 'auto'});
			this.listWrap = $('<div />' ).addClass(opts.listWrapCls).attr('id',opts.listId).hide().append(this.list).css({
				position: 'absolute',
				top: 0,
				zIndex : opts.zIndex
			});
			if (opts.extraListHtml) {
				$('<div />').addClass(opts.extraListHtmlCls).append(opts.extraListHtml).appendTo(this.listWrap);
			}
			if (opts.listRenderToBody) {
				if(!$.Combo.allListWrap) {
					$.Combo.allListWrap = $('<div id="COMBO_WRAP"/>').appendTo('body');
				}
				this.listWrap.appendTo($.Combo.allListWrap)
			} else {
				this.wrap.after(this.listWrap);
			}
		},
		

		/**
			* 设置下拉菜单宽高,默认自适应
		*/
		_setListLayout: function(){
			var opts = this.opts, listW, listH = parseInt(opts.listHeight), diffW = 0, originListH, 
			triggerW = this.trigger ? this.trigger.outerWidth() : 0, minListWidth = parseInt(opts.minListWidth), maxListWidth = parseInt(opts.maxListWidth);
			//外层可能设置过宽高，重置
			this.listWrap.width('auto');
			this.list.height('auto');

			this.listWrap.show();//为了获取list高度显示一下
			this.isExpanded = true;
			originListH = this.list.height();

			//设高
			if(!isNaN(listH) && listH >= 0){
				listH = Math.min(listH, originListH);
				this.list.height(listH);
			}

			if(opts.listWidth == 'auto' || opts.width == 'auto'){
				listW = this.listWrap.outerWidth();
				//自适应时，如有滚动条，补20滚动条宽，用高度判断是否出现滚动条
				if(originListH < this.list.height()){
					diffW = 20
					listW += diffW;
				}
			} else {
				listW = parseInt(opts.listWidth)
				isNaN(listW) ? listW = this.wrap.outerWidth() : null; 
			}

			if (opts.width == 'auto') {
				var comboW = this.listWrap.outerWidth() + Math.max(triggerW,diffW);
				this._setComboLayout(comboW);
			}

			//最小宽度 不能少于combo宽
			minListWidth = isNaN(minListWidth) ? this.wrap.outerWidth() : Math.max(minListWidth, this.wrap.outerWidth());
			if(!isNaN(minListWidth) && listW < minListWidth){
				listW = minListWidth;
			}
			//最大宽度
			if(!isNaN(maxListWidth) && listW > maxListWidth){
				listW = maxListWidth;
			}


			listW = listW  - (this.listWrap.outerWidth() - this.listWrap.width());
			this.listWrap.width(listW);

			this.listWrap.hide();
			this.isExpanded = false;

		},

		/**
			*w为总宽
		*/
		_setComboLayout: function(w){
			if(!w){return ;}
			var opts = this.opts, maxWidth = parseInt(opts.maxWidth), minWidth = parseInt(opts.minWidth);
			if (!isNaN(maxWidth) && w > maxWidth) {
				w = maxWidth;
			};
			if (!isNaN(minWidth) && w < minWidth) {
				w = minWidth;
			};
			var inputW;
			w = w - (this.wrap.outerWidth() - this.wrap.width());
			this.wrap.width(w);
			if (this.wrap[0] == this.input[0]) {
				return ;
			};
			inputW = w - (this.trigger ? this.trigger.outerWidth() : 0) - (this.input.outerWidth() - this.input.width());
			this.input.width(inputW);
		},
		
		/**
			* 设置下拉菜单定位，根据可视区大小定位
		*/
		_setListPosition: function() {
			if(!this.isExpanded){return ;}
			var opts = this.opts, top, left,
				win = $(window),
				wrapTop = this.wrap.offset().top,
				wrapLeft = this.wrap.offset().left,
				winH = win.height(),
				winW = win.width(),
				scrollTop = win.scrollTop(),
				scrollLeft = win.scrollLeft(),
				wrapH = this.wrap.outerHeight(),
				wrapW = this.wrap.outerWidth(),
				listH = this.listWrap.outerHeight(),
				listW = this.listWrap.outerWidth(),
				borderW = parseInt(this.listWrap.css('border-top-width'));
			top = (wrapTop - scrollTop + wrapH + listH) > winH && wrapTop > listH ? (wrapTop - listH + borderW) : (wrapTop + wrapH - borderW);
			left = (wrapLeft - scrollLeft + listW) > winW ? (wrapLeft + wrapW - listW) : wrapLeft;
			this.listWrap.css({
				top : top,
				left : left
			});
		},

		
		/**
			* 获取以何种方式渲染 local or remote
		*/
		_getRenderMode: function() {
			var mode, data = this.dataOpt;
			if($.isFunction(data)){
				data = data();
			}
			if($.isArray(data)){
				this.rawData = data;
				mode = 'local';
			}else if(typeof data == 'string'){
				this.url = data;
				mode = 'remote';
			}
			return mode;
		},

		/**
			* 请求AJAX数据，并添加到下拉
		*/
		_loadAjaxData : function(selected, flag, query){
			var self = this, opts = self.opts, ajaxOpts = opts.ajaxOptions, loading = $('<div />').addClass(opts.loadingCls).text(ajaxOpts.loadingText);
			self.list.append(loading);
			self.list.find(opts.listTipsCls).remove();
			self._setListLayout();
			self._setListPosition();
			self.xhr = $.ajax({
				url: self.url,
				type: ajaxOpts.type,
				dataType: ajaxOpts.dataType,
				timeout: ajaxOpts.timeout,
				success: function(data){
					loading.remove();
					self.doingQuery = false;
					if($.isFunction(ajaxOpts.success)){
						ajaxOpts.success(data);
					}
					if($.isFunction(ajaxOpts.formatData)){
						data = ajaxOpts.formatData(data);
					}
					if(!data){return ;}
					self.rawData = data;
					self._formatData();
					self._populateList(self.formattedData);
					if(selected === '') {
						self.lastQuery = query;
						self.filterData = self.formattedData;
						self.expand();
					} else {
						self._setDefaultSelected(selected, flag);
					}
					self.xhr = null;
					self.mode = self._getRenderMode();
				},
				error: function(xhr,textStatus,errorThrown){
					loading.remove();
					self.doingQuery = false;
					$('<div />').addClass(opts.tipsCls).text(ajaxOpts.errorText).appendTo(self.list);
					self.xhr = null;
				}
			});
		},
		

		/**
			* 获取ComboBox的是否disabled
		*/
		getDisabled: function() {
			return this._disabled;
		},

		/**
			* 获取ComboBox的值
		*/
		getValue: function() {
			if(this._selectedIndex > -1) {
				return this.formattedData[this._selectedIndex].value;
			} else {
				if (this.opts.forceSelection) {
					return '';
				} else {
					return this.input.val();
				};
			}
		},
		

		/**
			* 获取ComboBox的文本
		*/
		getText: function() {
			if(this._selectedIndex > -1) {
				return this.formattedData[this._selectedIndex].text;
			} else {
				if (this.opts.forceSelection) {
					return '';
				} else {
					return this.input.val();
				};
			}
		},

		/**
			* 获取ComboBox的selectedIndex
		*/
		getSelectedIndex: function(){
			return this._selectedIndex;
		},


		/**
			* 获取选中数据的原始数据项 
		*/
		getSelectedRow: function(){
			if(this._selectedIndex > -1){
				return this.rawData[this._selectedIndex];
			}
		},
		
		/**
			* 获取选中数据的原始数据项
		*/
		getDataRow: function(){
			if(this._selectedIndex > -1){
				return this.rawData[this._selectedIndex];
			}
		},
		
		getAllData: function(){
			return this.formattedData;
		},

		getAllRawData: function(){
			return this.rawData;
		},

		/**
			* 设置默认选项
		*/
		_setDefaultSelected: function(selected, flag){
			var opts = this.opts;
			if (typeof selected == 'function') {
				defaultSelected = defaultSelected.call(this,this.rawData);
			};
			if(!isNaN(parseInt(selected))){
				var selectedIndex = parseInt(selected);
				this._setSelected(selectedIndex, flag);
			} else if ( $.isArray(selected)) {
				this.selectByKey(selected[0], selected[1], flag);
			} else if (this.originSelect) {
				var selectedIndex = this.originSelect[0].selectedIndex;
				this._setSelected(selectedIndex, flag);
			} else if (opts.autoSelect) {
				this._setSelected(0, flag);
			};
		},

		/**
			* 通过索引选中，flag为是否触发回调，默认为触发，下同
		*/
		selectByIndex: function(selectedIndex, flag){
			this._setSelected(selectedIndex, flag);
		},

		/**
			* 通过文本值选中,如值不存在selectIndex 设为 -1
		*/
		selectByText: function(text, flag){
			if (!this.formattedData) {return ;}
			var formattedData = this.formattedData, selectedIndex = -1;
			for (var i = 0, len = formattedData.length; i < len; i++) {
				if (formattedData[i].text === text) {
					selectedIndex = i;
					break ;
				};
			};
			this._setSelected(selectedIndex, flag);
		},
		
		/**
			* 通过值选中, 如值不存在selectIndex 设为 -1
		*/
		selectByValue: function(value, flag){
			if (!this.formattedData) {return ;}
			var formattedData = this.formattedData, selectedIndex = -1;
			for (var i = 0, len = formattedData.length; i < len; i++) {
				if (formattedData[i].value === value) {
					selectedIndex = i;
					break ;
				};
			};
			this._setSelected(selectedIndex, flag);
		},

		/**
			* 通过原始数据的键值选中 值必须=== 如无匹配 selectedIndex设为 -1
		*/
		selectByKey: function(key, value, flag){
			if (!this.rawData) {return ;}
/*			var rawData = this.rawData, item, selectedIndex = -1;
			for(var i = 0, len = rawData.length; i < len; i++){
				if (rawData[i][key] === value) {
					selectedIndex = i;
					break ;
				};
			} */
			var self = this, opts= self.opts;
			var rawData = this.rawData, selectedIndex = -1;
			//修复数据变化
			if(opts.addOptions || opts.emptyOptions) {
				rawData = this.formattedData;
				for(var i = 0, len = rawData.length; i < len; i++){
					if (rawData[i].value === value) {
						selectedIndex = i;
						break ;
					};
				};
			} else {
				for(var i = 0, len = rawData.length; i < len; i++){
					if (rawData[i][key] === value) {
						selectedIndex = i;
						break ;
					};
				} 
			};
			this._setSelected(selectedIndex,flag);
		},

		/**
			* 直接选中下拉项
		*/
		selectByItem: function(item, flag){
			//无下拉项参数 或者 不是下拉项 返回
			if (!item || item.parent()[0] != this.list[0]) {return ;}
			var text = item.text();
			this.selectByText(text, flag);
		},


		/**
			* 执行选中select  有beforeChange 和 onChange两个回调
		*/
		_setSelected: function(selectedIndex, flag){
			var opts = this.opts , selectedIndex = parseInt(selectedIndex);
			var  flag = typeof flag != 'undefined' ? !!flag : true;
			if (isNaN(selectedIndex)) {return}


			//无选项数据 返回
			if (!this.formattedData || this.formattedData.length == 0) {
				this._selectedIndex = -1;
				return ;
			}

			var length = this.formattedData.length;	
			//超出数据索引范围均设为 -1
			if (selectedIndex < -1 || selectedIndex >= length) {
				selectedIndex = -1;
			}
			//与现索引相同 返回
			if (this._selectedIndex == selectedIndex) {return ;}

			var selectedData = selectedIndex == -1 ? null : this.formattedData[selectedIndex];
			var selectedRawData = selectedIndex == -1 ? null : selectedData.rawData;
			var text = selectedIndex == -1 ? '' : selectedData.text;
			var listItems = this.list.find('.' + opts.listItemCls);
			if(flag && typeof(this.beforeChange) == 'function' ){
				//原始数据作为参数回调
				if(!this.beforeChange(selectedRawData)){
					return ;
				}
			}

			
			//改变数据选中键值
			if (selectedIndex != -1) {
				//selectedData.selected = true;
				//TODO 考虑是否在这里为选中项加上 选中样式
				//listItems.removeClass(opts.selectedCls).filter('[data-value=' + selectedData.value + ']').addClass(opts.selectedCls);
			}

			if (!(opts.editable && selectedIndex == -1 && this.focus)) {
				this.input.val(text);
			};

			this._selectedIndex = selectedIndex;
			if(flag && typeof(this.onChange) == 'function'){
				this.onChange(selectedRawData);
			}
			if(this.originSelect){
				this.originSelect[0].selectedIndex = selectedIndex;
			}

		},


		removeSelected: function(flag){
			this.input.val('');
			this._setSelected(-1,flag);
		},


		/**
			*
		*/


		/**
			* 触发回调
		*/
		_triggerCallback: function(callback,args){
			//if () {};
		},
		


		/**
			*从原始的select中获取数据
		*/
		_getDataFromSelect: function() {
			var opts = this.opts, data = [];
			$.each(this.originSelect.find('option'), function(idx){
				var item = $(this), dataItem = {};
				dataItem[opts.text] = item.text();
				dataItem[opts.value] = item.attr('value');
				data.push(dataItem);
			});
			return data;
		},

		/**
			*格式化原始数据
		*/
		_formatData: function(){
			if(!$.isArray(this.rawData)){return ;}
			var self = this, opts= self.opts;
			self.formattedData =[];
			if(opts.emptyOptions) {
				self.formattedData.push({text:'(空)', value: 0});
			};
			if(opts.addOptions) {
				self.formattedData.push(opts.addOptions);
			};
			$.each(this.rawData, function(idx, row){
				var formattedRow = {}, value, text;
				formattedRow.text = $.isFunction(opts.formatText) ? opts.formatText(row) : row[opts.text];
				formattedRow.value = $.isFunction(opts.formatValue) ? opts.formatValue(row) : row[opts.value];
				formattedRow.rawData = row;
				self.formattedData.push(formattedRow);
			});
			self.formattedLen = self.formattedData.length;
		},


		/**
			*筛选匹配的本地数据，本地数据时用，远程数据带参数load更新
			*@ {param} {string} 匹配的字符，无参数或参数为空字符串则匹配全部数据
		*/
		_filter: function(query){
			query = typeof query == 'undefined' ? '' : query;
			//TODO 由于selectIndex改变会重复
			if (this.input.val() != this.getText()) {
				this.selectByText(this.input.val());
			};
			var opts = this.opts, self = this, maxFilter = opts.maxFilter;
			if(!this.opts.cache){
				if(this.mode == 'local' && $.isFunction(this.dataOpt)){
					this.rawData = this.dataOpt();
				}
				this._formatData();
			}

			if(!$.isArray(this.formattedData)){return ;}

			if(query == ''){
				this.filterData = this.formattedData;
			} else {
				this.filterData = [];
				var index_pos = [];
				$.each(self.formattedData, function(idx, item){
					var text = item.text;
					if($.isFunction(opts.customMatch)){
						//console.log(opts.customMatch(text,query));
						if(!opts.customMatch(item,query)){
							return ;
						}
					} else{
						var i = opts.caseSensitive ? '' : 'i';
						var reg = new RegExp(query.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, '\\$&'), i);
						if(text.search(reg) == -1){
							return ;
						}
					}
					self.filterData.push(item);
					index_pos.push({i: idx, val: item.value});
					if(self.filterData.length == opts.maxFilter){
						return false;
					}
				});
			};
			//过滤重复选项 START
			var filterDataObject = {};
			var newDilterData = [];
			for(var i = 0 ,  len = this.filterData.length; i < len; i++){
				var _fData = this.filterData[i];
				if(!filterDataObject[_fData.value]){
					filterDataObject[_fData.value] = true;
					newDilterData.push(_fData);
				}
			}
			this.filterData = newDilterData;
			newDilterData = [];
			filterDataObject = {};
			//过滤重复选项 END
			if(self.formattedLen === 100 && self.filterData.length < opts.maxFilter){
				//console.log(rawData)
				if(self.addQuery === true) {
					this.incrementalSearch(index_pos, callback);
				};
				//this.rawData = addData;
				//this.filterData = addData;
			} else {
				this.lastQuery = query;
				this.list.empty();
				this._populateList(this.filterData);
				this.expand();
				self.doingQuery = false;
			};
			
			function callback(){
				this._formatData();
				this.filterData = this.formattedData;
				this.lastQuery = query;
				this.list.empty();
				this._populateList(this.filterData);
				this.expand();
				self.doingQuery = false;
			}
			
		},


		/**
			*执行查询
		*/
		doDelayQuery: function(query){
			var self = this, opts = self.opts, delay = parseInt(opts.queryDelay);
			self.doingQuery = true;
			if(isNaN(delay)){
				delay = 0;
			}
			if(self.queryDelay){
				window.clearTimeout(self.queryDelay);
			}
			self.queryDelay = window.setTimeout(function(){
				self.doQuery(query);
			},delay);
		},

		/**
			*执行查询
		*/
		doQuery: function(query){
			if(this.mode == 'local' || (this.mode == 'remote' && this.opts.loadOnce)){
				this._filter(query);
			} else {
				this._loadAjaxData('', false, query);
			}
		},

		/**
			*根据格式化的数据生成下拉选项，并设定layout 和 position
			*@ param {array} 格式化过的数据
		*/
		_populateList : function(data){
			if(!data){return ;}
			var self = this, opts = self.opts;
			if(data.length == 0){
				if(opts.forceSelection){
					$('<div />').addClass(opts.tipsCls).html(opts.noDataText).appendTo(self.list);
					this._setListLayout();
				}
			} else{
				for (var i = 0, len = data.length; i < len; i++) {
					var item = data[i], text = item.text, value = item.value;
					var _$div = $('<div />').attr({
						'class': opts.listItemCls + (i == this._selectedIndex ? ' ' + opts.selectedCls : ''),
						'data-value': value
					});	
					if(opts.disStrict){
						_$div.html(text).appendTo(self.list);	
					}else{
						_$div.text(text).appendTo(self.list);	
					}
				}
				this._setListLayout();
			}
			//this.listItems = this.list.find('.' + opts.listItemCls);
			//this._setListPosition();
		},


		/**
			*展开下拉菜单
		*/
		expand: function(){
			var opts = this.opts;
			if(!this.active || this.isExpanded || (this.filterData.length == 0 && !opts.noDataText && !opts.extraListHtmlCls)){
				this.listWrap.hide();
				return ;
			}

			//if(this.isExpanded) {return ;}
			this.isExpanded = true;
			this.listWrap.show();
			this._setListPosition();
			if($.isFunction(this.onExpand)){
				this.onExpand();
			}
			var listItems = this.list.find('.' + opts.listItemCls);
			if( listItems.length == 0 ) {return ;}
			var item = listItems.filter('.' + opts.selectedCls);
			if (item.length == 0) {
				item = listItems.eq(0);
				if(opts.autoSelectFirst){
					item.addClass(opts.hoverCls);
				}
			}
			this._scrollToItem(item);
		},

		/**
			*收起下拉菜单
		*/
		collapse: function() {
			if(!this.isExpanded){ return ; }
			var opts = this.opts;
			this.listWrap.hide();
			this.isExpanded = false;
			if(this.listItems){
				this.listItems.removeClass(opts.hoverCls);
			}
			if($.isFunction(this.onCollapse)){
				this.onCollapse();
			}
		},

		_onTriggerClick: function(){
			if(this._disabled){
				return ;
			}
			this.active = true;
			this.input.focus();
			if(this.isExpanded){
				this.collapse();
			} else {
				this._filter();
			}
		},


		_scrollToItem: function(item){
			//console.log(item);
			if(!item || item.length == 0) {return ;}
			var viewTop = this.list.scrollTop();
			var itemTop = viewTop + item.position().top;
			var viewBottom = viewTop + this.list.height();//如果listwrap 有上下padding会让可视区有差别 暂不处理，listWrap不要加上下padding
			var itemBottom = itemTop + item.outerHeight();
			//this.list.scrollTop(this.list.scrollTop() + itemTop);
			//如不在可视区内，使选中项可视
			if(itemTop < viewTop || itemBottom > viewBottom){
				this.list.scrollTop(itemTop);
			}

		},

		
		_scrollPage: function(direction) {
			var viewTop = this.list.scrollTop();
			var viewH = this.list.height();
			var top;
			if(direction == 'up'){
				top = viewTop - viewH;
			} else if(direction == 'down'){
				top = viewTop + viewH;
			}
			this.list.scrollTop(top);
		},

		/*
			*使选项高亮，direction 为prev 或者 next
		*/
		_setItemFocus: function(direction){
			var opts = this.opts, idx, item, listItems = this.list.find('.' + opts.listItemCls);
			if (listItems.length == 0) {return ;}
			var focusItem = listItems.filter('.' + opts.hoverCls).eq(0);
			if (focusItem.length == 0) {
				focusItem = listItems.filter('.' + opts.selectedCls).eq(0);
			};
			if (focusItem.length == 0) {
				idx  = 0;
			} else {
				idx = listItems.index(focusItem);
				if (direction == 'next') {
					idx = (idx == listItems.length - 1) ? 0 : idx + 1;  
				} else {
					idx = idx == 0 ? listItems.length - 1 : idx - 1;
				}
			};
			item = listItems.eq(idx)
			listItems.removeClass(opts.hoverCls);
			item.addClass(opts.hoverCls);
			this._scrollToItem(item);
		},

		empty: function(flag){
			this._setSelected(-1, false);
			this.input.val('');
			this.list.empty();
			this.rawData = null;
			this.formattedData = null;
		},

		//设置编辑状态
		setEdit: function(){

		}

	}

	$.Combo.defaults = {
		data: null, //comboBox数据源 可为url 或者JSON，如果对象本身为select，取select的数据
		text: 'text', //选项的文本。若为string，视为数据项的键名，取键值为文本；(未做：//若为function，function的返回为文本，该function带数据项为参数。
		value: 'value', //选项的值。若为string，视为数据项的键名，取键值为选项的值；(未做：//若为function，function的返回为选项的值，该function带数据项为参数。
		formatText: null, //根据数据项来格式化文本值，
		formatValue: null, // 参上
		defaultSelected : undefined, //默认选中的项。如为数字或转为数字的字符串，视为索引，选中该索引项；如果为数组，第一项视为Key,第二项视为value，匹配对应项
    	defaultFlag: true,
    	autoSelect: true,
		disabled: undefined,
		editable: false,
		caseSensitive: false,
		forceSelection: true,
		cache: true,
		queryDelay: 100,
		maxFilter: 20,
		minChars: 0,
		customMatch: null,
		addQuery: '',
		noDataText: '没有匹配的选项',
		autoSelectFirst:true,//展开菜单时是否自动选中第一个

		width: undefined, //comboBox的总宽，默认由样式控制，如为auto根据list内容定宽
		minWidth: undefined,
		maxWidth: undefined,
		listWidth: undefined, //下拉菜单的总宽，默认等于等于comboBox的宽度，最小宽度等于comboBox的宽度，如为auto根据list内容定宽
		listHeight: 150, //下拉菜单高度，超过出滚动条显示
		maxListWidth: undefined,
		maxListWidth: undefined,
		zIndex: 1000,
		listRenderToBody: true,
		extraListHtml: undefined,
		disStrict: false,//非严格模式，可以支持下拉框出现html元素
		//pageLength: 10, //pageUp pageDown按下时跳过的选项数

		//ajax获取数据时的配置,部分与$.ajax的配置一致
		ajaxOptions: {
			type: 'post',
			dataType: 'json',
			queryParam: 'query',
			timeout: 10000,
			formatData: null, //对ajax返回的数据进行处理
			loadingText: 'Loading...',
			success: null,
			error: null,
			errorText: '数据加载失败'
		},
		loadOnce: true,

		id: undefined,
		listId: undefined,
		wrapCls: 'ui-combo-wrap',
		focusCls: 'ui-combo-focus',
		disabledCls: 'ui-combo-disabled',
		activeCls: 'ui-combo-active',
		inputCls: 'input-txt',
		triggerCls: 'trigger',
		listWrapCls: 'ui-droplist-wrap',
		listCls: 'droplist',
		listItemCls: 'list-item',
		selectedCls: 'selected',
		hoverCls: 'on',
		loadingCls: 'loading',
		tipsCls: 'tips',
		extraListHtmlCls: 'extra-list-ctn',

		//回调的this均指向comboBox实例
		callback: {
			onFocus : null,
			onBlur: null,
			beforeChange: null,
			onChange: null,
			onExpand: null,
			onCollapse: null,
			onEnter:null,
			onListClick:null
		}
	}
})(jQuery)

