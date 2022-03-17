import React from 'react';

import { GlobalContextProvider } from './context/Context';
import StartingFields from './components/StartingFields.js';

function App() {
    return (
        <GlobalContextProvider>
          <form className="job-manager-form">
            <StartingFields/>
          </form>
        </GlobalContextProvider>
    )
}

export default App;