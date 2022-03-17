import React from 'react';

import { GlobalContextProvider } from './context/Context';
import StartingFields from './components/StartingFields.js';

function App() {
    return (
        <GlobalContextProvider>
          <form>
            <StartingFields/>
            <input type="submit" value="Register" />
          </form>
        </GlobalContextProvider>
    )
}

export default App;