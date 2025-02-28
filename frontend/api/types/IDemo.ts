import HasId from '@/api/types/HasId'
import HasTimestamps from '@/api/types/HasTimestamps'
import SoftDeletes from '@/api/types/SoftDeletes'

export default interface IDemo extends HasId, HasTimestamps, SoftDeletes {
    name: string
}
