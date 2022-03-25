import React from 'react';

import { GlobalContextProvider } from './context/Context';
import StartingFields from './components/StartingFields.js';
import ProfileFields from './components/ProfileFields.js';

function App() {
    return (
        <GlobalContextProvider>
          <form className="job-manager-form">
            <StartingFields/>
            <ProfileFields/>
          </form>
        </GlobalContextProvider>
    )
}

export default App;