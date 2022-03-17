import React from 'react';

export default function StartingFields() {
    return (
        <>
            <fieldset class="fieldset--email fieldset-type-text">
				<label for="email">Email</label>
				<div class="field required-field">
					<input type="text" class="input-text" name="email" id="email" required="required" />
				</div>
			</fieldset>
            <fieldset class="fieldset--organisation fieldset-type-text">
				<label for="organisation">Organisation</label>
				<div class="field required-field">
					<input type="text" class="input-text" name="organisation" id="organisation" required="required" />
				</div>
			</fieldset>
        </>
    )
}

