import React, { useContext, useState } from 'react';

import { Context } from '../context/Context.js';

export default function ProfileFields() {
	const 	{ 
				emailAvailable,
				organisationAvailable,
			} = useContext(Context),
            {   
                orgTypes, 
                orgSizes 
            } = window?.wpOrgsData || {};

    if(!emailAvailable || !organisationAvailable) {
        return null;
    }

    return (
        <>
            <h2>Your Details</h2>
            <fieldset className="fieldset--first-name fieldset-type-text">
				<label htmlFor="first-name">First Name</label>
				<div className="field required-field">
					<input 
						id="first-name" 
						className="input-text" 
						name="first-name"
						type="text" 
						required="required"
					/>
				</div>
			</fieldset>
            <fieldset className="fieldset--last-name fieldset-type-text">
				<label htmlFor="last-name">Last Name</label>
				<div className="field required-field">
					<input 
						id="last-name" 
						className="input-text" 
						name="last-name"
						type="text" 
						required="required"
					/>
				</div>
			</fieldset>
            <fieldset className="fieldset--password fieldset-type-text">
				<label htmlFor="password">Password</label>
				<div className="field required-field">
                    <input 
						id="password" 
						className="input-text" 
						name="password"
						type="text" 
						required="required"
					/>
                </div>
            </fieldset>
            <h2>Organisation Details</h2>
            <input type="submit" value="Register" />
        </>
    )
}

