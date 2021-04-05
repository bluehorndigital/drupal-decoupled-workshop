import { useEffect, useState } from 'react'
import { NavLink, Switch, Route, Redirect } from "react-router-dom";
import 'bootstrap/dist/css/bootstrap.css'
import logo from './logo.svg'
import RouteComponentFactory from './RouteComponentFactory'
import AppContainer from './components/AppContainer'
import BlockContent from './components/BlockContent'

function Menu ({links}) {
  return (
    <ul className="nav nav-pills">
      {links.map(link => (
        <li key={link.id} className={'nav-item'}>
          <NavLink
            activeClassName="active"
            className="nav-link"
            exact
            to={link.attributes.url}>
            {link.attributes.title}
          </NavLink>
        </li>
      ))}
    </ul>
  )
}

function doFetch(url, callback) {
  fetch(url)
    .then(res => res.json())
    .then(json => callback(json.data))
}

function MainMenu() {
  const [menu, setMenu] = useState(null);

  useEffect(() => {
    doFetch(`${process.env.REACT_APP_API_URL}/jsonapi/menu_items/main`, setMenu)
  }, [])

  return menu === null ? null : <Menu key="main-menu" links={menu}/>
}

function Header() {
  return (
    <div className="container">
      <header className="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="/en" className="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
          <img src={logo} className="App-logo" alt="logo" />
        </a>
        <MainMenu />
      </header>
    </div>
  )
}

function App() {
  return (
    <div className="App">
      <Header />
      <AppContainer>
        <Switch>
          <Route exact={true} path={'/'}>
            <Redirect to={'/en'} />
          </Route>
          <Route exact={true} path={'/en'}>
            <BlockContent bundle={'banner_block'} uuid={'9aadf4a1-ded6-4017-a10d-a5e043396edf'} />
            <p>Hello</p>
          </Route>
          <Route component={RouteComponentFactory} />
        </Switch>
      </AppContainer>

    </div>
  );
}

export default App;
