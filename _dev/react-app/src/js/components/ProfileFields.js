import React, { useContext } from 'react';

import { Context } from '../context/Context.js';

export default function ProfileFields() {
	const 	{ 
				availabilityChecked,
				emailAvailable,
				organisationAvailable,
			} = useContext(Context),

            {   
                orgTypes, 
                orgSizes 
            } = window?.wpOrgsData || {},

			app = document.getElementById('org-registration-form'), 
			nonce = app.getAttribute('data-nonce'),
			action = app.getAttribute('data-action');

    if(!availabilityChecked || !emailAvailable || !organisationAvailable) {
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
			<fieldset className="fieldset--org-type">
				<label htmlFor="org-type">Organisation Type</label>
				<div className="field required-field">
					<select name="org-type" id="org-type" required="required">
						<option value="">Please select</option>
						{
							orgTypes.map(function(item, i){
								return <option key={i} value={item.ID}>{item.label}</option>
							})
						}
					</select>
				</div>
			</fieldset>
			<fieldset className="fieldset--org-size">
				<label htmlFor="org-type">Organisation Size</label>
				<div className="field required-field">
					<select name="org-size" id="org-size" required="required">
						<option value="">Please select</option>
						{
							orgSizes.map(function(item, i){
								return <option key={i} value={item.ID}>{item.label}</option>
							})
						}
					</select>
				</div>
			</fieldset>

			<input type="hidden" id="wp-orgs_nonce" name="wp-orgs_nonce" value={nonce}/>
			<input type="hidden" name="_wp_http_referer" value={action}/>
            <input type="submit" name="register-organisation" value="Register" />
        </>
    )
}

