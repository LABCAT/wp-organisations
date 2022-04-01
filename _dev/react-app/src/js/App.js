import React from 'react';

import { GlobalContextProvider } from './context/Context';
import StartingFields from './components/StartingFields.js';
import ProfileFields from './components/ProfileFields.js';

function App() {
    const app = document.getElementById('org-registration-form'), 
			action = app.getAttribute('data-action');
    return (
        <GlobalContextProvider>
          <form action={action} method="post" id="org-register-form" enctype="multipart/form-data" className="job-manager-form">
            <StartingFields/>
            <ProfileFields/>
          </form>
        </GlobalContextProvider>
    )
}

export default App;