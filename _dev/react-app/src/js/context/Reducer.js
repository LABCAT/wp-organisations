export default function reducer(state, action) {
    switch(action.type){
        case "SET_AVAILABILITY_CHECKED": {
            return {
                ...state,
                availabilityChecked: action.payload
            }
        }
        case "SET_EMAIL_IS_AVAILABLE": {
            return {
                ...state,
                emailAvailable: action.payload
            }
        }
        case "SET_ORGANISATION_IS_AVAILABLE": {
            return {
                ...state,
                organisationAvailable: action.payload
            }
        }
        default: 
            throw new Error('Action type does not exist!')
    }
}