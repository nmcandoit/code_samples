/***************************************************

     Example of drawer for a react-native app

***************************************************/

import React, {
    Component
} from "react";
import DrawerLayout from 'react-native-gesture-handler/DrawerLayout';
import {
    Linking,
    Image,
    TouchableHighlight
} from "react-native";
import {
    Container,
    Icon,
    Text,
    Card,
    CardItem,
    Content
} from "native-base";
import {
    Avatar
} from "react-native-elements";

export default class Drawer extends Component {

    renderDrawer = () => {
        console.log('render launched');
        return ( <
            Container style = {
                {
                    backgroundColor: '#eeedee'
                }
            } >
            <
            Content >
            <
            Card style = {
                {
                    height: 400
                }
            } >
            <
            CardItem >
            <
            Avatar

            rounded xlarge activeOpacity = {
                0.7
            }
            source = {
                require('../../assets/images/dav-r.jpeg')
            }
            /> <
            /CardItem> <
            CardItem header >
            <
            Text > Full Name: < /Text> <
            Text style = {
                {
                    color: 'grey',
                    textTransform: 'uppercase',
                    fontSize: 14
                }
            } > {
                'David Rénaers'.toUpperCase()
            } <
            /Text> <
            /CardItem> <
            CardItem >
            <
            Text > Status: < /Text> <
            Text note style = {
                {
                    color: 'grey'
                }
            } > Regular Shooter < /Text> <
            Icon name = 'ios-medal'
            style = {
                {
                    color: '#f9c543',
                    marginLeft: 10,
                    marginBottom: 5
                }
            }
            /> <
            /CardItem> <
            CardItem >
            <
            Text > Email: < /Text> <
            Text note style = {
                {
                    color: 'grey'
                }
            } > myemail@domain.com < /Text> <
            /CardItem> <
            CardItem >
            <
            Text > Contact: < /Text> <
            Text note style = {
                {
                    color: 'grey'
                }
            } > +32 12 345 67 89 < /Text> <
            /CardItem> <
            /Card> <
            Card style = {
                {
                    height: 290
                }
            } >
            <
            Image style = {
                {
                    width: 130,
                    height: 100,
                    marginLeft: 50,
                    justifyContent: 'center',
                    alignItems: 'center',

                }
            }
            source = {
                require('../../assets/images/Shootlog.png')
            }
            /> <
            Text style = {
                {
                    color: 'grey',
                    justifyContent: 'center',
                    alignItems: 'center',
                    marginLeft: 85,
                    fontSize: 10
                }
            } >
            Version: 1.0 .2 < /Text> <
            Text style = {
                {
                    color: 'grey',
                    justifyContent: 'center',
                    alignItems: 'center',
                    marginLeft: 70,
                    fontSize: 15
                }
            } >
            Developed by: < /Text>

            <
            TouchableHighlight onPress = {
                () => Linking.openURL('http://candoit.be')
            } >
            <
            Image style = {
                {
                    width: 80,
                    height: 70,
                    marginLeft: 75,
                    justifyContent: 'center',
                    alignItems: 'center'
                }
            }
            source = {
                require('../../assets/images/logo_candoit.png')
            }
            /> <
            /TouchableHighlight>




            <
            Text style = {
                {
                    color: 'grey',
                    justifyContent: 'center',
                    alignItems: 'center',
                    marginLeft: 85,
                    fontSize: 10
                }
            }
            onPress = {
                () => Linking.openURL('http://candoit.be')
            } >
            www.candoit.be < /Text> <
            Text style = {
                {
                    color: 'grey',
                    justifyContent: 'center',
                    alignItems: 'center',
                    marginLeft: 15,
                    fontSize: 12
                }
            } >
            All Rights Reserved.®2018 by CanDoIt < /Text> <
            /Card> <
            /Content>

            <
            DrawerLayout drawerWidth = {
                250
            }
            drawerPosition = {
                DrawerLayout.positions.Left
            }
            drawerType = 'front'
            drawerBackgroundColor = '#ddd'
            renderNavigationView = {
                this.renderDrawer
            }
            /> <
            /Container>
        );

    }


};

const drawerStyles = {
    drawer: {
        shadowColor: '#000000',
        shadowOpacity: 0.8,
        shadowRadius: 3
    },
    main: {
        paddingLeft: 3
    },
}
