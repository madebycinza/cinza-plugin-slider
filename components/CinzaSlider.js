"use strict";

class CinzaSlider extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      name: "Vinicius"
    };
  }

  render() {
    return /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("p", null, "Are you okay with Cookies? ", this.state.name));
  }

}

ReactDOM.render( /*#__PURE__*/React.createElement(CinzaSlider, null), document.querySelector(".cslider-container"));