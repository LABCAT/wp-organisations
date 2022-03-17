import React, { useState } from 'react';

export default function StartingFields() {
	const app = document.getElementById('org-registration-form'), 
		ajaxURL = app.getAttribute('data-ajax-url'),

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
					.then((json) => console.log(json))
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
						onChange={e => setEmail(e.target.value)}
						required="required"
					 />
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
						onChange={e => setOrganisation(e.target.value)}
					/>
				</div>
			</fieldset>
			<input type="submit" value="Register" onClick={checkAvailability} />
        </>
    )
}

