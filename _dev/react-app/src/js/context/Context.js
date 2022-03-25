import React, { createContext, useReducer } from 'react';

import Reducer from "./Reducer";

const initialState = {
    emailAvailable: false,
    organisationAvailable: false,
}

export const Context = createContext(initialState);

export const GlobalContextProvider = ({ children }) => {
    const [state, dispatch] = useReducer(Reducer, initialState);

    const setEmailAvailable = (status) => {
        dispatch({ type: "SET_EMAIL_IS_AVAILABLE", payload: status });
    }

    const setOrganisationAvailable = (status) => {
        dispatch({ type: "SET_ORGANISATION_IS_AVAILABLE", payload: status });
    }

    return <Context.Provider
                value={
                    {
                        emailAvailable: state.emailAvailable,
                        organisationAvailable: state.organisationAvailable,
                        setEmailAvailable,
                        setOrganisationAvailable
                    }
                }
            >
                {children}
            </Context.Provider>
};
