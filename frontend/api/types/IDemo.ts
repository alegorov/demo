import HasId from '@/api/types/HasId'
import HasTimestamps from '@/api/types/HasTimestamps'
import SoftDeletes from '@/api/types/SoftDeletes'
import {ModelStatus} from '@/api/types/ModelStatus'

export default interface IDemo extends HasId, HasTimestamps, SoftDeletes {
    name: string
    email: string
    status: ModelStatus
}
