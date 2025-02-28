import CrudRouteBuilder from '@/component/model/crud/CrudRouteBuilder'
import {MODEL_INFO} from '@/utils/model-info'

export default CrudRouteBuilder
    .for(MODEL_INFO.DEMO)
    .featureTable(() => import('./DemoIndex.vue'))
    .edit(() => import('./DemoEdit.vue'))
    .create(() => import('./DemoEdit.vue'))
    .view(() => import('./DemoView.vue'))
    .build()
