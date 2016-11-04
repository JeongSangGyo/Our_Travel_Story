<div ng-controller="planController" class="content-grid mdl-grid" style="max-width: 100%; max-height: 100%;">
  <!-- <md-progress-linear md-mode="indeterminate"></md-progress-linear> -->
  <div class="mdl-cell mdl-cell--4-col" flex style="height: 90%">
      <!-- <div layout="row" style="border: 1px solid blue;">
        <div layout="column" style="border: 1px solid red;">
          <h4>{{story_name}}</h4>
          <md-datepicker ng-model="myDate" md-placeholder="Enter date" style="margin: 0px;"></md-datepicker>
          <div layout="row" layout-align="center stretch">
            <div layout="column" layout="start none">
              <md-input-container class="md-block" style="margin: 10px 0 0 0;">
                <label>Nation or Region</label>
                <input ng-model="search.region">
              </md-input-container>
              <md-input-container class="md-block" style="margin: 10px 0 0 0;">
                <label>Place Name</label>
                <input ng-model="search.place">
              </md-input-container>
              <md-button class="searchBtn" ng-click="to_place_search()">
                검색
              </md-button>
            </div>
          </div>
        </div>
      </div>
      <div layout="row" layout="center stretch" style="border: 1px solid blue;">
        <div layout="column" style="border: 2px solid #E91E63; height: 270px; !important; margin-bottom: 15px;" flex>
          <md-toolbar layout="row" layout-align="center center" style="background-color: #E91E63; color: white; font-weight: bold; font-size: 24px;">
            접속자
          </md-toolbar>
          <div flex layout="column" style="overflow:auto; overflow-x:hidden; overflow-y:auto;">
            <span>cyka</span>
          </div>
        </div>
      </div> -->
      <div layout="row">
        <div layout="column" flex="50" layout-padding>
          <div layout="column">
            <div flex="20">
              <h4 style="margin-top: 0px;">{{story_name}}</h4>
            </div>
            <div flex="80">
              <div layout="column">
                <md-input-container class="md-block" style="margin: 0;">
                  <label>国・地域</label>
                  <input ng-model="search.region">
                </md-input-container>
                <md-input-container class="md-block" style="margin: 0;">
                  <label>名前</label>
                  <input ng-model="search.place">
                </md-input-container>
                <md-button class="searchBtn" ng-click="to_place_search()">
                    検索
                </md-button>
              </div>
            </div>
          </div>
          <div style="padding: 0;">
            <md-tabs md-selected="placeCategoryIndex" md-border-bottom md-autoselect>
              <md-tab ng-repeat="placeCategory in placeCategories" ng-disabled="placeCategory.disabled" label="{{placeCategory.title}}">
                <md-tab-label>
                  <i class="material-icons">{{placeCategory.icon}}</i>
                </md-tab-label>
              </md-tab>
            </md-tabs>

            <md-card>
              <md-button ng-click="showAdvanced($event)">
              <md-card-header style="padding: 0;">
                <div layout="row" layout-align="center none">
                <md-card-avatar style="width: 50px; height: 50px; margin: 5px;">
                  <i class="material-icons dp48" style="font-size: 48px; width: 48px; height: 48px;">add_location</i>
                </md-card-avatar>
                <md-card-header-text style="margin: 5px;">
                  <div layout="column" layout-align="center none">
                    <span class="md-title">旅先　追加</span>
                    <span class="md-subhead">どこへ行きたいですか？</span>
                  </div>
                </md-card-header-text>
                </div>
              </md-card-header>
            </md-button>
            <script type="text/ng-template" id="additional_place.html">
              <md-dialog aria-label="Additional_place" ng-cloak style="width: 75%; height: 80%;">
                <form>
                  <md-toolbar>
                    <div class="md-toolbar-tools">
                      <h2 style="font-size: 20px; font-weight: bold; color: white;">旅先　追加</h2>
                      <span flex></span>
                      <md-button class="md-icon-button" ng-click="cancel()">
                        <i class="material-icons" aria-label="Close dialog">clear</i>
                      </md-button>
                    </div>
                  </md-toolbar>

                  <md-dialog-content layout="column" style="height: 1080px;" layout-margin layout-padding>
                    <div layout="row" flex="80" layout-padding>
                      <div layout="column" flex="33">
                        <md-input-container class="md-block" style="margin: 10px 0 0 0;">
                          <label>旅先　名前</label>
                          <input ng-model="place.name">
                        </md-input-container>
                        <md-input-container class="md-block" style="margin: 10px 0 0 0;">
                          <label>旅先　電話番号</label>
                          <input ng-model="place.phone">
                        </md-input-container>

                        <md-tabs md-selected="additionalPlaceCategoryIndex" md-border-bottom md-autoselect>
                          <md-tab ng-repeat="additionalPlaceCategory in additionalPlaceCategories" label="{{additionalPlaceCategory.title}}">
                            <md-tab-label>
                              <i class="material-icons">{{additionalPlaceCategory.icon}}</i>
                            </md-tab-label>
                          </md-tab>
                        </md-tabs>

                        <md-input-container class="md-block">
                          <label>一言で表現すれば…</label>
                          <textarea ng-model="place.description" md-maxlength="30" rows="3" md-select-on-focus></textarea>
                        </md-input-container>

                        <md-input-container class="md-block">
                          <label>旅先に対する詳しい説明</label>
                          <textarea ng-model="place.detail_description" md-maxlength="30" rows="5" md-select-on-focus></textarea>
                        </md-input-container>
                      </div>

                      <div layout="column" flex="66">
                        <div flex="20">
                          <md-input-container id="place_add_search" md-no-float class="md-block">
                            <input ng-model="place.address" placeholder="旅先　住所" ng-keypress="place_add_search($event)">
                          </md-input-container>
                        </div>
                        <div flex="80" style="margin: 0px; padding: 0;">
                          <additional-place-map></additional-place-map>
                        </div>
                      </div>
                    </div>
                    <div layout="row" flex="20" layout-padding>
                      <div flex="10">
                        <div layout="column" layout-align="center center" ngf-drop ngf-select ng-model="files" class="drop-box"
                             ngf-drag-over-class="'dragover'" ngf-multiple="true" ngf-allow-dir="true"
                             accept="image/*" ngf-resize="width: 100, height: 100, quality: 1.0" ngf-pattern="'image/*'" style="height: 100px;">
                             <div>写真　追加</div>
                        </div>
                      </div>
                      <div flex="80">
                        <md-content layout="row">
                          <md-card ng-repeat="image in files" style="width: 130px; height: 110px; margin-top: 0px;">
                            <img ngf-src="image" ngf-style="{width: 100%; heigth: 100%}">
                          </md-card>
                        </md-content>
                      </div>
                      <div flex="10">
                        <md-button class="searchBtn" ng-click="toAddPlace()" style="font-weight: 800;">
                          登録
                        </md-button>
                      </div>
                    </div>
                  </md-dialog-content>

                  <!-- <md-dialog-actions>
                    <md-button ng-click="toAddPlace()" class="md-primary" style="font-weight: 800;">
                      저장
                    </md-button>
                  </md-dialog-actions> -->
                </form>
              </md-dialog>
            </script>
            </md-card>

            <!-- <div layout="row" layout-align="space-around center">
              <div ng-repeat="place_search_filter in place_search_filters">
                <md-checkbox class="md-primary" ng-checked="exists(place_search_filter, place_search_filter_selected)" ng-click="toggle(place_search_filter, place_search_filter_selected)">
                  {{place_search_filter}}
                </md-checkbox>
              </div>
            </div> -->
          </div>
        </div>
        <div flex="50">
          <div layout="column" style="border: 2px solid #0277BD; height: 370px; !important;" flex>
            <md-toolbar layout="row" layout-align="center center" style="background-color: #0277BD; color: white; font-weight: bold; font-size: 24px; height: 20px;">
             　ブックマーク
            </md-toolbar>
            <div flex layout="column" style="overflow:auto; overflow-x:hidden; overflow-y:auto;">
              <md-content flex layout-margin data-drop="true" jqyoui-droppable="{multiple: true, onDrop: 'dropCallback'}">
                <md-card ng-value="{{data.PlaceIdx}}" data-drag="true" data-jqyoui-options="{helper: 'clone', appendTo: 'body'}" jqyoui-draggable="{animate:true, placeholder: 'keep', revert: true}"
                  flex layout-margin ng-repeat="data in recommend_data track by $index" md-gutter="0.5em" style="padding: 0;" ng-click="showPlaceInfo_share(data.PlaceIdx)">
                  <md-card-header style="padding: 0;">
                    <div layout="row" layout-align="center none">
                    <md-card-header-text style="margin: 5px;">
                      <div>
                        <span class="md-title">{{data.PlaceName}}</span>
                        <!-- <span class="md-subhead"></span> -->
                        <i class="material-icons" style="float: right;" ng-click="removePlaceInfo_share(data.PlaceIdx)">clear</i>
                      </div>
                    </md-card-header-text>
                    </div>
                  </md-card-header>
                </md-card>
              </md-content>
            </div>
          </div>
          <!-- <div layout="column" id="calendarTrash" style="height: 50px; !important; text-align: center; border: 3px dashed grey;" flex data-drop="true" jqyoui-droppable="{onDrop: 'delStoryPlaceCallback'}">

          </div> -->
        </div>
      </div>
    <!-- <div layout="column" layout-align="start none">
      <div layout="column" style="height: 490px !important; background-color: white;">
        <div flex layout="column" layout-align="center" style="overflow:auto; overflow-x:hidden; overflow-y:auto;">
          <md-content layout="row" layout-margin>
            <md-card ng-value="{{data.PlaceIdx}}" data-drag="true" data-jqyoui-options="{helper: 'clone', appendTo: 'body'}" jqyoui-draggable="{animate:true, placeholder: 'keep'}"
              flex="50" layout-margin ng-repeat="data in search_data" ng-model="search_data" md-gutter="0.5em" style="padding: 0;" ng-click="showPlaceInfo($index)">
              <md-card-header style="padding: 0;">
                <div layout="row" layout-align="center none">
                <md-card-avatar style="width: 50px; height: 50px; margin: 5px;" ng-switch on="data.CategoryPlaceIdx">
                  <i class="material-icons dp48" style="font-size: 48px; width: 48px; height: 48px;" ng-switch-when="1">time_to_leave</i>
                  <i class="material-icons dp48" style="font-size: 48px; width: 48px; height: 48px;" ng-switch-when="2">restaurant</i>
                  <i class="material-icons dp48" style="font-size: 48px; width: 48px; height: 48px;" ng-switch-when="3">hotel</i>
                  <i class="material-icons dp48" style="font-size: 48px; width: 48px; height: 48px;" ng-switch-when="4">local_grocery_store</i>
                  <i class="material-icons dp48" style="font-size: 48px; width: 48px; height: 48px;" ng-switch-when="5">account_balance</i>
                  <i class="material-icons dp48" style="font-size: 48px; width: 48px; height: 48px;" ng-switch-when="6">help</i>
                </md-card-avatar>
                <md-card-header-text style="margin: 5px;">
                  <div layout="column" layout-align="center none">
                    <span class="md-title">{{data.PlaceName}}</span>
                    <span class="md-subhead">{{data.PlaceNation}}, {{data.PlaceRegion}}</span>
                  </div>
                </md-card-header-text>
                </div>
              </md-card-header>
            </md-card>
          </md-content>
        </div>
      </div>
    </div> -->
    <div>
    <md-content layout-padding>
      <div layout="row" layout-align-gt-xs="colunm" style="height: 540px !important; background-color: white;">
        <div flex style="overflow-x:hidden; overflow-y:auto;">
          <md-grid-list md-cols="2" md-row-height="1:1" md-gutter="8px">
            <md-grid-tile ng-repeat="data in search_data" ng-mouseover="show = true" ng-mouseleave="show = false"
              md-rowspan="1"
              md-colspan="1"
              md-colspan-sm="1"
              md-colspan-xs="1"
              ng-class="green"
              ng-click="showPlaceInfo($index)">
              <ul rn-carousel rn-carousel-index="carouselIndex" style="width: 100%; height: 100%;">
                <li ng-repeat="place_image in data.PlaceImages">
                  <img ng-src="http://167.88.115.33/images/place/{{place_image.PlaceIdx}}/{{place_image.PlaceImageName}}.{{place_image.PlaceImageExt}}" style="width: 100%; height: 100%;" />
                </li>
                <div class="rn-carousel-controls ng-scope" ng-if="show">
                  <span class="rn-carousel-control rn-carousel-control-prev ng-scope" ng-click="prevSlide(); $event.stopPropagation();" ng-if="carouselIndex > 0 || false" style="color: white; :active:"></span>
                  <span class="rn-carousel-control rn-carousel-control-next ng-scope" ng-click="nextSlide(); $event.stopPropagation();" ng-if="carouselIndex < data.PlaceImages.length - 1 || false" style="color: white;"></span>
                </div>
              </ul>
              <md-grid-tile-footer
                ng-value="{{data.PlaceIdx}}"
                ng-model="search_data"
                data-drag="true" data-jqyoui-options="{helper: 'clone', appendTo: 'body'}" jqyoui-draggable="{animate:true, placeholder: 'keep'}"
                style="background: rgba(0, 0, 0, 0.68); height: 36px;">
                <span style="margin: 0; font-weight: 700; width: 100%; text-align: center; font-size: 24px;">{{data.PlaceName}}</span>
              </md-grid-tile-footer>
            </md-grid-tile>
          </md-grid-list>
        </div>
      </div>
    </md-content>
  </div>
  </div>

  <!-- <div class="mdl-cell mdl-cell--1-col">
    <div layout="column" style="border: 2px solid #0277BD; height: 640px; !important;" flex>
      <md-toolbar layout="row" layout-align="center center" style="background-color: #0277BD; color: white; font-weight: bold; font-size: 24px;">
        북마크
      </md-toolbar>
      <div flex layout="column" style="overflow:auto; overflow-x:hidden; overflow-y:auto;">
        <md-content flex layout-margin data-drop="true" jqyoui-droppable="{multiple: true, onDrop: 'dropCallback'}">
          <md-card ng-value="{{data.PlaceIdx}}" data-drag="true" data-jqyoui-options="{helper: 'clone', appendTo: 'body'}" jqyoui-draggable="{animate:true, placeholder: 'keep', revert: true}"
            flex layout-margin ng-repeat="data in recommend_data track by $index" md-gutter="0.5em" style="padding: 0;" ng-click="showPlaceInfo_share(data.PlaceIdx)">
            <md-card-header style="padding: 0;">
              <div layout="row" layout-align="center none">
              <md-card-header-text style="margin: 5px;">
                <div layout="column" layout-align="center none">
                  <span class="md-title">{{data.PlaceName}}</span>
                  <span class="md-subhead"></span>
                </div>
              </md-card-header-text>
              </div>
            </md-card-header>
          </md-card>
        </md-content>
      </div>
    </div>
    <div layout="column" id="calendarTrash" style="height: 100px; !important; text-align: center;" flex data-drop="true" jqyoui-droppable="{onDrop: 'delStoryPlaceCallback'}">
      <img ng-src="http://127.0.0.1/assets/img/garbage.png" style="width: 100%; height: 100%; margin-top: 5%;"/>
    </div>
  </div> -->

  <div class="mdl-cell mdl-cell--8-col">
    <div layout="column" layout-align="start none" flex>
      <div class="map-container" ng-style="myStyle">
        <google-map ng-style="myStyle"></google-map>
        <script type="text/ng-template" id="detail_place_info.html">
          <md-dialog aria-label="Additional_companion" ng-cloak style="width: 80%; height: 80%;" flex>
              <md-toolbar>
                <div class="md-toolbar-tools">
                  <h2 style="font-size: 20px; font-weight: bold; color: white;">{{detailPlaceInfo.PlaceName}}</h2>
                  <span flex></span>
                  <md-button class="md-icon-button" ng-click="cancel()">
                    <i class="material-icons" aria-label="Close dialog">clear</i>
                  </md-button>
                </div>
              </md-toolbar>
              <md-content layout="row" layout-padding layout-align="center stretch" style="height: 100%;">
           
              </md-content>
          </md-dialog>
        </script>
      </div>
      <div id="plan_calendar" ui-calendar="uiConfig.calendar" ng-model="eventSources" calendar="myCalendar" ng-hide="is_map_wide"></div>
    </div>
  </div>

  <div class="lock-size" layout="row" layout-align="center center" ng-cloak>
    <md-fab-speed-dial class="md-scale" md-open="isDialog" md-direction="up" ng-cloak>
      <md-fab-trigger>
        <md-button aria-label="menu" class="md-fab md-warn">
          <i class="material-icons" style="font-size: 40px;">add</i>
        </md-button>
      </md-fab-trigger>
      <md-fab-actions>
        <md-button aria-label="Summary" class="md-fab md-raised" ng-click="showSummary()">
          <i class="material-icons" style="font-size: 44px;">import_contacts</i>
          <md-tooltip md-visible="demo.showTooltip" md-direction="left">
            旅行要約
          </md-tooltip>
        </md-button>
        <md-button aria-label="Companion" class="md-fab md-raised" ng-click="showCompanion($event)">
          <i class="material-icons" style="font-size: 44px;">people</i>
          <md-tooltip md-visible="demo.showTooltip" md-direction="left">
            同行者
          </md-tooltip>
        </md-button>
        <md-button aria-label="Material" class="md-fab md-raised" ng-click="showMaterial($event)">
          <i class="material-icons" style="font-size: 44px;">work</i>
          <md-tooltip md-visible="demo.showTooltip" md-direction="left">
            準備物
          </md-tooltip>
        </md-button>
      </md-fab-actions>
    </md-fab-speed-dial>
    <script type="text/ng-template" id="additional_companion.html">
      <md-dialog aria-label="Additional_companion" ng-cloak style="min-width: 800px; max-width:800px; min-height: 300px; max-height: 800px;" flex>
        <div>
          <md-toolbar>
            <div class="md-toolbar-tools">
              <h2 style="font-size: 20px; font-weight: bold; color: white;">同行者</h2>
              <span flex></span>
              <md-button class="md-icon-button" ng-click="cancel()">
                <i class="material-icons" aria-label="Close dialog">clear</i>
              </md-button>
            </div>
          </md-toolbar>
          <md-content layout-padding layout-align="center stretch">
            <div layout="row" layout-align-gt-xs="colunm" style="height: 570px !important;">
              <div flex="50" layout-padding style="overflow-x:hidden; overflow-y:auto;">
                <md-slider ng-model="companion_photo_value" step="1" min="2" max="4" flex md-discrete aria-label="rating"></md-slider>
                <md-grid-list md-cols="{{companion_photo_value}}" md-row-height="1:1" md-gutter="8px">
                  <md-grid-tile ng-repeat="companion in companions"
                    md-rowspan="1"
                    md-colspan="1"
                    md-colspan-sm="1"
                    md-colspan-xs="1"
                    ng-class="green">
                    <img src="http://167.88.115.33/images/user_profile_image/{{companion.MemberProfile}}.{{companion.MemberProfileExt}}" alt="Bookmark" style="width: 100%; height: 100%;">
                    <md-grid-tile-footer style="background: rgba(0, 0, 0, 0.68); height: 36px;">
                      <span style="margin: 0; font-weight: 700; width: 100%; text-align: center; font-size: 24px;">{{companion.MemberNickname}}</span>
                    </md-grid-tile-footer>
                  </md-grid-tile>
                </md-grid-list>
              </div>
              <div flex="50" layout-padding>
                <md-content>
                  <md-autocomplete
                    ng-disabled="isDisabled"
                    md-no-cache="noCache"
                    md-selected-item="selectedItem"
                    md-selected-item-change="selectedItemChange(searchText)"
                    md-search-text="searchText"
                    md-items="item in searchTextChange(searchText)"
                    md-item-text="item.MemberNickname"
                    md-min-length="4"
                    placeholder="Companion Search">
                    <md-item-template>
                      <span md-highlight-text="searchText" md-highlight-flags="^i">{{item.MemberNickname}}</span>
                    </md-item-template>
                    <md-not-found>
                      No Person matching "{{searchText}}" were found.
                    </md-not-found>
                  </md-autocomplete>
                </md-content>
                <md-list flex>
                  <md-list-item ng-repeat="item in companion_search_info" ng-click="do_member_info_action($evnet, item.MemberNickname)" class="noright">
                    <img ng-src="http://167.88.115.33/images/user_profile_image/{{item.MemberProfile}}.{{item.MemberProfileExt}}" class="md-avatar" alt="{{item.MemberNickname}}" />
                    <p>{{ item.MemberNickname }}</p>
                    <md-icon md-font-library="material-icons" ng-click="do_companion_request_action($event, item.MemberNickname)" class="md-secondary md-hue-3" style="font-size: 24px; text-align: center;">add_circle_outline</md-icon>
                  </md-list-item>
                </md-list>
              </div>
            </div>
          </md-content>
        </div>
      </md-dialog>
    </script>
    <script type="text/ng-template" id="additional_material.html">
      <md-dialog aria-label="Additional_place" ng-cloak style="min-width: 800px; max-width:800px; min-height: 300px; max-height: 800px;">
        <div>
          <md-toolbar>
            <div class="md-toolbar-tools">
              <h2 style="font-size: 20px; font-weight: bold; color: white;">準備物</h2>
              <span flex></span>
              <md-button class="md-icon-button" ng-click="cancel()">
                <i class="material-icons" aria-label="Close dialog">clear</i>
              </md-button>
            </div>
          </md-toolbar>
          <md-content layout-padding>
            <div layout="row">
              <h2 class="md-title">私の準備物</h2>
            </div>
            <div layout="row">
              <md-chips ng-model="material" readonly="false" md-on-add="add_material($chip)" md-on-remove="delete_material($chip)"></md-chips>
            </div>
            <div layout="row" style="padding-top: 60px;">
              <md-input-container flex>
                <label>Foods</label>
                  <md-select ng-model="selectedFoods"
                     md-on-close="clearSearchTerm()"
                     data-md-container-class="selectdemoSelectHeader"
                     multiple>
                    <md-optgroup label="foods">
                      <md-option ng-value="food" ng-repeat="food in foods">
                        {{food}}
                      </md-option>
                    </md-optgroup>
                  </md-select>
              </md-input-container>
              <md-input-container flex>
                <label>Accessory</label>
                  <md-select ng-model="selectedAccessories"
                     md-on-close="clearSearchTerm()"
                     data-md-container-class="selectdemoSelectHeader"
                     multiple>
                    <md-optgroup label="accessory">
                      <md-option ng-value="accessory" ng-repeat="accessory in accessories track by $index">
                        {{accessory}}
                      </md-option>
                    </md-optgroup>
                  </md-select>
              </md-input-container>
              <md-input-container flex>
                <label>Drugs</label>
                  <md-select ng-model="selectedDrugs"
                     md-on-close="clearSearchTerm()"
                     data-md-container-class="selectdemoSelectHeader"
                     multiple>
                    <md-optgroup label="drugs">
                      <md-option ng-value="drug" ng-repeat="drug in drugs">
                        {{drug}}
                      </md-option>
                    </md-optgroup>
                  </md-select>
              </md-input-container>
              <md-input-container flex>
                <label>etcs</label>
                  <md-select ng-model="selectedEtcs"
                     md-on-close="clearSearchTerm()"
                     data-md-container-class="selectdemoSelectHeader"
                     multiple>
                    <md-optgroup label="etcs">
                      <md-option ng-value="etc" ng-repeat="etc in etcs">
                        {{etc}}
                      </md-option>
                    </md-optgroup>
                  </md-select>
              </md-input-container>
              <md-input-container flex>
                <label>recommend</label>
                  <md-select ng-model="selectedRecommendes"
                     md-on-close="clearSearchTerm()"
                     data-md-container-class="selectdemoSelectHeader"
                     multiple>
                    <md-optgroup label="recommendes">
                      <md-option ng-value="recommend" ng-repeat="recommend in recommendes">
                        {{recommend}}
                      </md-option>
                    </md-optgroup>
                  </md-select>
              </md-input-container>
            </div>
          </md-content>
        </div>
      </md-dialog>
    </script>
  </div>

  <!-- <div class="mdl-cell mdl-cell--1-col">
    <div layout="column" layout-align="center none" flex>
      <md-button class="materialBtn" ng-click="showMaterial($event)">
        준비물
      </md-button>
      <script type="text/ng-template" id="additional_material.html">
        <md-dialog aria-label="Additional_place" ng-cloak style="min-width: 800px; max-width:800px; min-height: 300px; max-height: 800px;">
          <div>
            <md-toolbar>
              <div class="md-toolbar-tools">
                <h2 style="font-size: 20px; font-weight: bold; color: white;">준비물</h2>
                <span flex></span>
                <md-button class="md-icon-button" ng-click="cancel()">
                  <i class="material-icons" aria-label="Close dialog">clear</i>
                </md-button>
              </div>
            </md-toolbar>
            <md-content layout-padding>
              <div layout="row">
                <h2 class="md-title">나의 준비물</h2>
              </div>
              <div layout="row">
                <md-chips ng-model="material" readonly="false" md-on-add="add_material($chip)" md-on-remove="delete_material($chip)"></md-chips>
              </div>
              <div layout="row" style="padding-top: 60px;">
                <md-input-container flex>
                  <label>Foods</label>
                    <md-select ng-model="selectedFoods"
                       md-on-close="clearSearchTerm()"
                       data-md-container-class="selectdemoSelectHeader"
                       multiple>
                      <md-optgroup label="foods">
                        <md-option ng-value="food" ng-repeat="food in foods">
                          {{food}}
                        </md-option>
                      </md-optgroup>
                    </md-select>
                </md-input-container>
                <md-input-container flex>
                  <label>Accessory</label>
                    <md-select ng-model="selectedAccessories"
                       md-on-close="clearSearchTerm()"
                       data-md-container-class="selectdemoSelectHeader"
                       multiple>
                      <md-optgroup label="accessory">
                        <md-option ng-value="accessory" ng-repeat="accessory in accessories track by $index">
                          {{accessory}}
                        </md-option>
                      </md-optgroup>
                    </md-select>
                </md-input-container>
                <md-input-container flex>
                  <label>Drugs</label>
                    <md-select ng-model="selectedDrugs"
                       md-on-close="clearSearchTerm()"
                       data-md-container-class="selectdemoSelectHeader"
                       multiple>
                      <md-optgroup label="drugs">
                        <md-option ng-value="drug" ng-repeat="drug in drugs">
                          {{drug}}
                        </md-option>
                      </md-optgroup>
                    </md-select>
                </md-input-container>
                <md-input-container flex>
                  <label>etcs</label>
                    <md-select ng-model="selectedEtcs"
                       md-on-close="clearSearchTerm()"
                       data-md-container-class="selectdemoSelectHeader"
                       multiple>
                      <md-optgroup label="etcs">
                        <md-option ng-value="etc" ng-repeat="etc in etcs">
                          {{etc}}
                        </md-option>
                      </md-optgroup>
                    </md-select>
                </md-input-container>
                <md-input-container flex>
                  <label>recommend</label>
                    <md-select ng-model="selectedRecommendes"
                       md-on-close="clearSearchTerm()"
                       data-md-container-class="selectdemoSelectHeader"
                       multiple>
                      <md-optgroup label="recommendes">
                        <md-option ng-value="recommend" ng-repeat="recommend in recommendes">
                          {{recommend}}
                        </md-option>
                      </md-optgroup>
                    </md-select>
                </md-input-container>
              </div>
            </md-content>
          </div>
        </md-dialog>
      </script>
      <md-button class="companionBtn" ng-click="showCompanion($event)">
        동행자
      </md-button>
      <script type="text/ng-template" id="additional_companion.html">
        <md-dialog aria-label="Additional_companion" ng-cloak style="min-width: 800px; max-width:800px; min-height: 300px; max-height: 800px;" flex>
          <div>
            <md-toolbar>
              <div class="md-toolbar-tools">
                <h2 style="font-size: 20px; font-weight: bold; color: white;">동행자</h2>
                <span flex></span>
                <md-button class="md-icon-button" ng-click="cancel()">
                  <i class="material-icons" aria-label="Close dialog">clear</i>
                </md-button>
              </div>
            </md-toolbar>
            <md-content layout-padding layout-align="center stretch">
              <div layout="row" layout-align-gt-xs="colunm" style="height: 570px !important;">
                <div flex="50" layout-padding style="overflow-x:hidden; overflow-y:auto;">
                  <md-slider ng-model="companion_photo_value" step="1" min="2" max="4" flex md-discrete aria-label="rating"></md-slider>
                  <md-grid-list md-cols="{{companion_photo_value}}" md-row-height="1:1" md-gutter="8px">
                    <md-grid-tile ng-repeat="companion in companions"
                      md-rowspan="1"
                      md-colspan="1"
                      md-colspan-sm="1"
                      md-colspan-xs="1"
                      ng-class="green">
                      <img src="http://167.88.115.33/images/user_profile_image/{{companion.MemberProfile}}.{{companion.MemberProfileExt}}" alt="Bookmark" style="width: 100%; height: 100%;">
                      <md-grid-tile-footer style="background: rgba(0, 0, 0, 0.68); height: 36px;">
                        <span style="margin: 0; font-weight: 700; width: 100%; text-align: center; font-size: 24px;">{{companion.MemberNickname}}</span>
                      </md-grid-tile-footer>
                    </md-grid-tile>
                  </md-grid-list>
                </div>
                <div flex="50" layout-padding>
                  <md-content>
                    <md-autocomplete
                      ng-disabled="isDisabled"
                      md-no-cache="noCache"
                      md-selected-item="selectedItem"
                      md-selected-item-change="selectedItemChange(searchText)"
                      md-search-text="searchText"
                      md-items="item in searchTextChange(searchText)"
                      md-item-text="item.MemberNickname"
                      md-min-length="4"
                      placeholder="Companion Search">
                      <md-item-template>
                        <span md-highlight-text="searchText" md-highlight-flags="^i">{{item.MemberNickname}}</span>
                      </md-item-template>
                      <md-not-found>
                        No Person matching "{{searchText}}" were found.
                      </md-not-found>
                    </md-autocomplete>
                  </md-content>
                  <md-list flex>
                    <md-list-item ng-repeat="item in companion_search_info" ng-click="do_member_info_action($evnet, item.MemberNickname)" class="noright">
                      <img ng-src="http://167.88.115.33/images/user_profile_image/{{item.MemberProfile}}.{{item.MemberProfileExt}}" class="md-avatar" alt="{{item.MemberNickname}}" />
                      <p>{{ item.MemberNickname }}</p>
                      <md-icon md-font-library="material-icons" ng-click="do_companion_request_action($event, item.MemberNickname)" class="md-secondary md-hue-3" style="font-size: 24px; text-align: center;">add_circle_outline</md-icon>
                    </md-list-item>
                  </md-list>
                </div>
              </div>
            </md-content>
          </div>
        </md-dialog>
      </script>
      <md-button class="summaryBtn" ng-click="showSummary()">
        여행 요약
      </md-button>
    </div>
    <div layout="column" style="border: 2px solid #0277BD; height: 700px; !important;" flex>
      <md-toolbar layout="row" layout-align="center center" style="background-color: #0277BD; color: white; font-weight: bold; font-size: 24px;">
        북마크
      </md-toolbar>
      <div flex layout="column" style="overflow:auto; overflow-x:hidden; overflow-y:auto;">
        <md-content flex layout-margin data-drop="true" jqyoui-droppable="{multiple: true, onDrop: 'dropCallback'}">
          <md-card ng-value="{{data.PlaceIdx}}" data-drag="true" data-jqyoui-options="{helper: 'clone', appendTo: 'body'}" jqyoui-draggable="{animate:true, placeholder: 'keep', revert: true}"
            flex layout-margin ng-repeat="data in recommend_data track by $index" md-gutter="0.5em" style="padding: 0;" ng-click="showPlaceInfo_share(data.PlaceIdx)">
            <md-card-header style="padding: 0;">
              <div layout="row" layout-align="center none">
              <md-card-header-text style="margin: 5px;">
                <div layout="column" layout-align="center none">
                  <span class="md-title">{{data.PlaceName}}</span>
                  <span class="md-subhead"></span>
                </div>
              </md-card-header-text>
              </div>
            </md-card-header>
          </md-card>
        </md-content>
      </div>
    </div>
    <div layout="column" id="calendarTrash" style="height: 140px; !important;" flex data-drop="true" jqyoui-droppable="{onDrop: 'delStoryPlaceCallback'}">
      <img ng-src="http://167.88.115.33/assets/img/garbage.png" style="width: 90%; height: 90%; margin-top: 10%; margin-left: 5%;"/>
    </div>
  </div> -->

  <!-- <div class="mdl-cell mdl-cell--2-col" style="color: black; height: 100%; background-color: white;">
    <div layout="column" style="height: 1025px; !important; border: 2px solid #FF1744;" flex>
      <md-toolbar style="background-color: #FF1744;">
        <div class="md-toolbar-tools">
          <span layout="row" layout-align="center center" style="color: white; font-weight: bold; font-size: 24px;">
            <span>채팅</span>
          </span>
        </div>
      </md-toolbar>
      <md-content flex="15" style="border: 1px solid blue;">
        <p ng-repeat="conn in connector track by $index">{{conn}}</p>
      </md-content>
      <md-content flex="70" style="height: 75%;">
        <p ng-repeat="message in messagise track by $index">{{message}}</p>
      </md-content>
      <md-content layout="column" flex="15" class="md-padding md-default-theme">
        <div layout="row" layout-align="center end">
          <md-input-container class="md-block">
             <label>Story Chat</label>
             <input ng-model="send_message" ng-keypress="keyPress($event)">
           </md-input-container>
        </div>
      </md-content>
    </div>
  </div> -->
</div>
