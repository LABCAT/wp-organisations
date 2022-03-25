import React, { useContext, useState } from 'react';

import { Context } from '../context/Context.js';

export default function StartingFields() {
	const 	{ 
				emailAvailable,
				organisationAvailable,
				setEmailAvailable, 
				setOrganisationAvailable,
			} = useContext(Context),

			app = document.getElementById('org-registration-form'), 
			ajaxURL = app.getAttribute('data-ajax-url'),

			[ availabilityChecked, setAvailabilityChecked ] = useState(false),
			[ email, setEmail ] = useState(''),
			[ organisation, setOrganisation ] = useState(''),

			validateEmail = () => {
				const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
				return re.test(String(email).toLowerCase());
			},

			isFormValid = () => {
				return email && organisation && validateEmail();
			},

			checkAvailability = async (e) => {
				if(isFormValid()) {
					e.preventDefault();
					const formData = new FormData();
					formData.append('action', 'availability_check');
					formData.append('email', email)
					formData.append('organisation', organisation)
					await fetch(ajaxURL,
						{
							method: 'post',
							body: formData,
						})
						.then((response: Response) => response.json())
						.then((json) => {
								console.log(json)
								setAvailabilityChecked(true);
								setEmailAvailable(json.emailAvailable);
								setOrganisationAvailable(json.orgAvailable);
							}
						)
						.catch((error) => console.log(error));
				}
			};

    return (
        <>
            <fieldset className="fieldset--email fieldset-type-text">
				<label htmlFor="email">Email</label>
				<div className="field required-field">
					<input 
						id="email" 
						className="input-text" 
						name="email"
						type="email" 
						value={email}
						onChange={e => {
								setAvailabilityChecked(false);
								setEmail(e.target.value)
							}
						}
						required="required"
					/>
					{ 
						!availabilityChecked ?
						<></> :
							emailAvailable ?
							<p className="job-manager-message">Available</p> :
							<p className="job-manager-error">Sorry this email is already in use.</p>
					}
				</div>
			</fieldset>
            <fieldset className="fieldset--organisation fieldset-type-text">
				<label htmlFor="organisation">Organisation</label>
				<div className="field required-field">
					<input 
						id="organisation"
						className="input-text"
						name="organisation"
						type="text"    
						required="required" 
						value={organisation}
						onChange={e => {
								setAvailabilityChecked(false);
								setOrganisation(e.target.value)
							}
						}
					/>
					{ 
						!availabilityChecked ?
						<></> :
							organisationAvailable ?
						<p className="job-manager-message">Available</p> :
						<p className="job-manager-error">Sorry this organisation already exists, please contact the administrator if you would like to be added to the organisation.</p>
					}
				</div>
			</fieldset>
			{ 
				!availabilityChecked &&
				<input type="submit" value="Register" onClick={checkAvailability} />
			}
        </>
    )
}

