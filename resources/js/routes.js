import Dashboard from './components/Dashboard.vue';
import ListAppointments from './pages/Appointments/ListAppointments.vue';
import ListUsers from './pages/Users/ListUsers.vue';
import Settings from './pages/Settings/Settings.vue';
import Profile from './pages/Profile/Profile.vue';

export default [
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: Dashboard,
    },
    {
        path: '/admin/appointments',
        name: 'admin.appointments',
        component: ListAppointments,
    },
    {
        path: '/admin/users',
        name: 'admin.users',
        component: ListUsers,
    },
    {
        path: '/admin/settings',
        name: 'admin.settings',
        component: Settings,
    },
    {
        path: '/admin/profile',
        name: 'admin.profile',
        component: Profile,
    },

];
