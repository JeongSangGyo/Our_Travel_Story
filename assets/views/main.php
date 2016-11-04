<div ng-controller="indexController" layout="row" flex style="width: 100%; height: 100%;">
  <div flex="10" style="border: 1px solid yellow">
    <button ng-click="toPlan()">Create Story</button>
  </div>

  <div flex="10" layout="column" layout-align="start" style="border: 1px solid red;">
    <md-button md-dynamic-height md-border-bottom class="md-primary" style="font-weight: bold; font-family: Courier; font-size: 20px;">Home</md-button>
    <md-button md-dynamic-height md-border-bottom class="md-primary" style="font-weight: bold; font-family: Courier; font-size: 20px;">Best</md-button>
    <md-button md-dynamic-height md-border-bottom class="md-primary" style="font-weight: bold; font-family: Courier; font-size: 20px;">5월 추천일정</md-button>
  </div>



  <div flex="70" style="overflow-y: auto; border:1px solid blue;">
    <div style="width: 100%; height: 300px;"></div>
    <div style="width: 100%; height: 300px;"></div>
    <div style="width: 100%; height: 300px;"></div>
    <div style="width: 100%; height: 300px;"></div>
    <div style="width: 100%; height: 300px;"></div>
  </div>

  <div flex="10" style="border: 1px solid yellow">
    <md-button class="md-fab md-fab-top-left left" style="background-color: skyblue;">
      <md-icon md-svg-src="" style="width: 24px; height: 24px;"></md-icon>
      <md-tooltip md-visible="false" md-direction="" style="font-size: 16px;">
        Create Story
      </md-tooltip>
    </md-button>
  </div>
</div>
